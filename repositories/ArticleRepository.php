<?php

namespace Wame\ArticleModule\Repositories;

use Wame\ArticleModule\Entities\ArticleEntity;
use Wame\ArticleModule\Entities\ArticleLangEntity;
use Wame\UserModule\Entities\UserEntity;
use Wame\ArticleModule\Exception;

class ArticleRepository extends \Wame\Core\Repositories\BaseRepository
{
	const STATUS_REMOVE = 0;
	const STATUS_PUBLISHED = 1;
	const STATUS_UNPUBLISHED = 2;
	
	/** @var ArticleEntity */
	private $articleEntity;
	
	/** @var UserEntity */
	private $userEntity;
	
	public function __construct(
		\Nette\DI\Container $container, 
		\Kdyby\Doctrine\EntityManager $entityManager, 
		\h4kuna\Gettext\GettextSetup $translator, 
		\Nette\Security\User $user
	) {
		parent::__construct($container, $entityManager, $translator, $user);
		
		$this->articleEntity = $this->entityManager->getRepository(ArticleEntity::class);
		$this->userEntity = $this->entityManager->getRepository(UserEntity::class)->findOneBy(['id' => $user->id]);
	}
	
	
	/**
	 * Get all status list
	 * 
	 * @return array
	 */
	public function getStatusList()
	{
		return [
			self::STATUS_REMOVE => _('Delete'),
			self::STATUS_PUBLISHED => _('Published'),
			self::STATUS_UNPUBLISHED => _('Unpublished')
		];
	}
	
	
	/**
	 * Get one status title
	 * 
	 * @param int $status
	 * @return string
	 */
	public function getStatus($status)
	{
		return $this->getStatusList($status);
	}
	
	
	/**
	 * Publish status list
	 * 
	 * @return array
	 */
	public function getPublishStatusList()
	{
		return [
			self::STATUS_PUBLISHED => _('Published'),
			self::STATUS_UNPUBLISHED => _('Unpublished')
		];
	}
	
	
	/**
	 * Add article
	 * 
	 * @param array $values
	 * @throws Exception\ArticleNotCreatedException
	 */
	public function add($values)
	{
		$articleEntity = new ArticleEntity();
		if ($values['publish_start_date']) {
			$articleEntity->publisStartDate = $this->formatDate($values['publish_start_date']);
		}
		if ($values['publish_end_date']) {
			$articleEntity->publisEndDate = $this->formatDate($values['publish_end_date']);
		}
		$articleEntity->createDate = $this->formatDate('now');
		$articleEntity->createUser = $this->userEntity;
		$articleEntity->status = $values['status'];

		$articleLangEntity = new ArticleLangEntity();
		$articleLangEntity->article = $articleEntity;
		$articleLangEntity->lang = $this->lang;
		$articleLangEntity->title = $values['title'];
		$articleLangEntity->slug = $values['slug'];
		$articleLangEntity->description = $values['description'];
		$articleLangEntity->text = $values['text'];
		$articleLangEntity->editDate = $this->formatDate('now');
		$articleLangEntity->editUser = $this->userEntity;

		$this->entityManager->persist($articleLangEntity);

		$create = $this->entityManager->persist($articleEntity);

		if (!$create) {
			throw new Exception\ArticleNotCreatedException(_('Could not create the article'));
		}
	}
	
	
	/**
	 * Set article
	 * 
	 * @param int $articleId
	 * @param array $values
	 */
	public function set($articleId, $values)
	{
		$articleEntity = $this->articleEntity->findOneBy(['id' => $articleId]);
		if ($values['publish_start_date']) {
			$articleEntity->publishStartDate = $this->formatDate($values['publish_start_date']);
		} else {
			$articleEntity->publishStartDate = null;
		}
		if ($values['publish_end_date']) {
			$articleEntity->publishEndDate = $this->formatDate($values['publish_end_date']);
		} else {
			$articleEntity->publishEndDate = null;
		}
		$articleEntity->status = $values['status'];
		
		$articleLangEntity = $this->entityManager->getRepository(ArticleLangEntity::class)->findOneBy(['article' => $articleEntity, 'lang' => $this->lang]);
		$articleLangEntity->title = $values['title'];
		$articleLangEntity->slug = $values['slug'];
		$articleLangEntity->description = $values['description'];
		$articleLangEntity->text = $values['text'];
		$articleLangEntity->editDate = $this->formatDate('now');
		$articleLangEntity->editUser = $this->userEntity;
	}
	
	
	/**
	 * Get one article by criteria
	 * 
	 * @param array $criteria
	 * @return ArticleEntity
	 */
	public function get($criteria = [])
	{
		$articleEntity = $this->articleEntity->findOneBy($criteria);

		return $articleEntity;
	}
	
	
	/**
	 * Get all articles by criteria
	 * 
	 * @param array $criteria
	 * @return ArticleEntity
	 */
	public function getAll($criteria = [])
	{
		$articleEntity = $this->articleEntity->findBy($criteria);

		return $articleEntity;
	}
	
	
	/**
	 * Delete articles by criteria
	 * 
	 * @param array $criteria
	 * @param int $status
	 */
	public function delete($criteria = [], $status = self::STATUS_REMOVE)
	{
		$articleEntity = $this->articleEntity->findOneBy($criteria);
		$articleEntity->status = $status;
	}
	
}