<?php

namespace Wame\ArticleModule\Repositories;

use Nette\Utils\DateTime;
use Wame\ArticleModule\Entities\ArticleEntity;
use Wame\ArticleModule\Entities\ArticleLangEntity;
use Wame\UserModule\Entities\UserEntity;
use Wame\ArticleModule\Exception;

class ArticleRepository extends \Wame\Core\Repositories\BaseRepository
{
	const STATUS_REMOVE = 0;
	const STATUS_PUBLISHED = 1;
	const STATUS_UNPUBLISHED = 2;
	
	/** @var UserEntity */
	private $userEntity;
	
	public function __construct(
		\Nette\DI\Container $container, 
		\Kdyby\Doctrine\EntityManager $entityManager, 
		\h4kuna\Gettext\GettextSetup $translator, 
		\Nette\Security\User $user
	) {
		parent::__construct($container, $entityManager, $translator, $user);
		
		$this->userEntity = $this->entityManager->getRepository(UserEntity::class)->findOneBy(['id' => $user->id]);
	}
	
	public function getStatusList()
	{
		return [
			self::STATUS_REMOVE => _('Delete'),
			self::STATUS_PUBLISHED => _('Published'),
			self::STATUS_UNPUBLISHED => _('Unpublished')
		];
	}
	
	public function getStatus($status)
	{
		return $this->getStatusList($status);
	}
	
	public function getPublishStatusList()
	{
		return [
			self::STATUS_PUBLISHED => _('Published'),
			self::STATUS_UNPUBLISHED => _('Unpublished')
		];
	}
	
	public function addArticle($values)
	{
		$articleEntity = new ArticleEntity();
		if ($values['publish_start_date']) {
			$articleEntity->publisStartDate = new DateTime('Y-m-d H:i:s', strtotime($values['publish_start_date']));
		}
		if ($values['publish_end_date']) {
			$articleEntity->publisEndDate = new DateTime('Y-m-d H:i:s', strtotime($values['publish_end_date']));
		}
		$articleEntity->createDate = new DateTime('now');
		$articleEntity->createUser = $this->userEntity;
		$articleEntity->status = $values['status'];

		$articleLangEntity = new ArticleLangEntity();
		$articleLangEntity->article = $articleEntity;
		$articleLangEntity->lang = $this->lang;
		$articleLangEntity->title = $values['title'];
		$articleLangEntity->slug = $values['slug'];
		$articleLangEntity->description = $values['description'];
		$articleLangEntity->text = $values['text'];
		$articleLangEntity->editDate = new DateTime('now');
		$articleLangEntity->editUser = $this->userEntity;

		$this->entityManager->persist($articleLangEntity);

		$create = $this->entityManager->persist($articleEntity);

		if (!$create) {
			throw new Exception\ArticleNotCreatedException(_('Could not create the article'));
		}
	}
	
	public function getArticles($criteria = [])
	{
		$articleEntity = $this->entityManager->getRepository(ArticleEntity::class)->findBy($criteria);

		return $articleEntity;
	}
	
}