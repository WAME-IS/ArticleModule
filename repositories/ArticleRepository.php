<?php

namespace Wame\ArticleModule\Repositories;

use Wame\ArticleModule\Entities\ArticleEntity;
use Wame\ArticleModule\Entities\ArticleLangEntity;
use Wame\UserModule\Entities\UserEntity;

class ArticleRepository extends \Wame\Core\Repositories\BaseRepository /*implements \Wame\Core\Repositories\Icrud*/
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
		parent::__construct($container, $entityManager, $translator, $user, ArticleEntity::CLASS);
		
//		dump('ArticleRepository construct'); exit;
		
//		$this->entity = $this->entityManager->getRepository(ArticleEntity::class);
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
	
	public function findByFilter($filter = [])
	{
		$qb = $this->entityManager->createQueryBuilder();
		$qb->select('a')->from(ArticleEntity::class, 'a');
		$this->addOrderByFilter($qb, $filter);
		$this->addDateFilter($qb, $filter);
		$this->addStatusFilter($qb, $filter);
		$this->addPagerFilter($qb, $filter);
		
		return $qb->getQuery()->getResult();
	}
	
	public function countByFilter($filter = [])
	{
		$qb = $this->entityManager->createQueryBuilder();
		$qb->select('COUNT(1)')->from(ArticleEntity::class, 'a');
		$this->addDateFilter($qb, $filter);
		
		return $qb->getQuery()->getSingleScalarResult();
	}
	
	private function addDateFilter($qb, $filter)
	{
		if(array_key_exists('year', $filter) && array_key_exists('month', $filter)) {
			$initialDate = (new \DateTime())->setDate($filter['year'], $filter['month'], 1);
			$finalDate = (new \DateTime())->setDate($filter['year'], $filter['month']+1, 1);
			
			$qb->andWhere('a.createDate BETWEEN :initialDate AND :finalDate')
					->setParameter('initialDate', $initialDate)
					->setParameter('finalDate', $finalDate);
		}
	}
	
	private function addStatusFilter($qb, $filter)
	{
		if(array_key_exists('status', $filter)) {
			$qb->andWhere('a.status = :status')
					->setParameter('status', $filter['status']);
		}
	}
	
	private function addOrderByFilter($qb, $filter)
	{
		if(array_key_exists('orderBy', $filter)) {
			switch($filter['orderBy']) {
				default:
				case 'name':
					$qb->innerJoin(ArticleLangEntity::class, 'l');
					$qb->orderBy('l.title', 'ASC');
					break;
				case 'date':
					$qb->orderBy('a.createDate', 'ASC');
					break;
			}
		}
	}
	
	private function addPagerFilter($qb, $filter)
	{
		if(array_key_exists('offset', $filter) && array_key_exists('limit', $filter))
		{
			$qb->setFirstResult($filter['offset'])
				->setMaxResults($filter['limit']);
		}
	}
	
	
	/**
	 * Add article
	 * 
	 * @param array $values
	 * @throws Exception\ArticleNotCreatedException
	 */
	public function create($articleLangEntity)
	{
		// TODO: overit ci nezalezi na poradi

		$create = $this->entityManager->persist($articleLangEntity->article);
		$this->entityManager->persist($articleLangEntity);
		$this->entityManager->flush();
		if (!$create) {
			throw new \Wame\Core\Exception\RepositoryException(_('Could not create the article'));
		}
		
		return $articleLangEntity->article;
	}
	
	
	public function update($articleLangEntity)
	{
		
		return $articleLangEntity->article;
	}
	
//	/**
//	 * Set article
//	 * 
//	 * @param int $articleId
//	 * @param array $values
//	 */
//	public function update($articleId, $values)
//	{
//		$articleEntity = $this->entity->findOneBy(['id' => $articleId]);
//		if ($values['publish_start_date']) {
//			$articleEntity->publishStartDate = $this->formatDate($values['publish_start_date']);
//		} else {
//			$articleEntity->publishStartDate = null;
//		}
//		if ($values['publish_end_date']) {
//			$articleEntity->publishEndDate = $this->formatDate($values['publish_end_date']);
//		} else {
//			$articleEntity->publishEndDate = null;
//		}
//		$articleEntity->status = $values['status'];
//		
//		$articleLangEntity = $this->entityManager->getRepository(ArticleLangEntity::class)->findOneBy(['article' => $articleEntity, 'lang' => $this->lang]);
//		$articleLangEntity->title = $values['title'];
//		$articleLangEntity->slug = $values['slug'];
//		$articleLangEntity->description = $values['description'];
//		$articleLangEntity->text = $values['text'];
//		$articleLangEntity->editDate = $this->formatDate('now');
//		$articleLangEntity->editUser = $this->userEntity;
//	}
	
	/**
	 * Delete articles by criteria
	 * 
	 * @param array $criteria
	 * @param int $status
	 */
	public function delete($criteria = [], $status = self::STATUS_REMOVE)
	{
		$articleEntity = $this->entity->find($criteria);
		$articleEntity->status = $status;
	}
	
}