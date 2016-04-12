<?php

namespace Wame\ArticleModule\Repositories;

use Nette\Security\User;
use Wame\ArticleModule\Entities\ArticleEntity;
use Wame\UserModule\Entities\UserEntity;

class ArticleRepository extends \Wame\Core\Repositories\BaseRepository
{
	const TABLE_NAME = 'article';
	
	const STATUS_REMOVE = 0;
	const STATUS_PUBLISHED = 1;
	const STATUS_UNPUBLISHED = 2;
	
	/** @var UserEntity */
	private $userEntity;
	
	public function __construct(
		\Nette\DI\Container $container, 
		\Kdyby\Doctrine\EntityManager $entityManager,
		User $user
	) {
		parent::__construct($container, $entityManager, self::TABLE_NAME);
		
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
			$articleEntity->publisStartDate = date('Y-m-d H:i:s', strtotime($values['publish_start_date']));
		}
		if ($values['publish_end_date']) {
			$articleEntity->publisEndDate = date('Y-m-d H:i:s', strtotime($values['publish_end_date']));
		}
		
		$articleEntity->createDate = new \DateTime('now');
		$articleEntity->createUser = $this->userEntity;
		$articleEntity->status = self::STATUS_PUBLISHED;
		
		return $this->entityManager->persist($articleEntity);
	}
	
}