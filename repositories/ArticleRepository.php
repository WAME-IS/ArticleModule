<?php

namespace Wame\ArticleModule\Repositories;

use Wame\ArticleModule\Entities\ArticleEntity;
use Wame\ArticleModule\Entities\ArticleLangEntity;
use Wame\UserModule\Entities\UserEntity;
use Wame\Core\Exception\RepositoryException;

class ArticleRepository extends \Wame\Core\Repositories\BaseRepository
{
	const STATUS_REMOVE = 0;
	const STATUS_PUBLISHED = 1;
	const STATUS_UNPUBLISHED = 2;
	
//	/** @var ArticleEntity */
//	private $articleEntity;
	
	/** @var UserEntity */
	private $userEntity;
	
	
	public function __construct(
		\Nette\DI\Container $container, 
		\Kdyby\Doctrine\EntityManager $entityManager, 
		\h4kuna\Gettext\GettextSetup $translator, 
		\Nette\Security\User $user
	) {
		parent::__construct($container, $entityManager, $translator, $user, ArticleEntity::CLASS);
		
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
	
//	public function findByFilter($filter = [])
//	{
//		$qb = $this->entityManager->createQueryBuilder();
//		$qb->select('a')->from(ArticleEntity::class, 'a');
//		$this->addOrderByFilter($qb, $filter);
//		$this->addDateFilter($qb, $filter);
//		$this->addAuthorFilter($qb, $filter);
//		$this->addStatusFilter($qb, $filter);
//		$this->addPagerFilter($qb, $filter);
//		
//		return $qb->getQuery()->getResult();
//	}
//	
//	public function countByFilter($filter = [])
//	{
//		$qb = $this->entityManager->createQueryBuilder();
//		$qb->select('COUNT(1)')->from(ArticleEntity::class, 'a');
//		$this->addDateFilter($qb, $filter);
//		$this->addAuthorFilter($qb, $filter);
//		$this->addStatusFilter($qb, $filter);
//		
//		return $qb->getQuery()->getSingleScalarResult();
//	}
	
//	private function addDateFilter($qb, $filter)
//	{
//		if(array_key_exists('year', $filter) && array_key_exists('month', $filter)) {
//			$initialDate = (new \DateTime())->setDate($filter['year'], $filter['month'], 1);
//			$finalDate = (new \DateTime())->setDate($filter['year'], $filter['month']+1, 1);
//			
//			$qb->andWhere('a.createDate BETWEEN :initialDate AND :finalDate')
//					->setParameter('initialDate', $initialDate)
//					->setParameter('finalDate', $finalDate);
//		}
//	}
//	
//	private function addAuthorFilter($qb, $filter)
//	{
//		if(array_key_exists('author', $filter)) {
//			$qb->andWhere('a.createUser = :author')
//					->setParameter('author', $filter['author']);
//		}
//	}
//	
//	private function addStatusFilter($qb, $filter)
//	{
//		if(array_key_exists('status', $filter)) {
//			$qb->andWhere('a.status = :status')
//					->setParameter('status', $filter['status']);
//		}
//	}
//	
//	private function addOrderByFilter($qb, $filter)
//	{
//		if(array_key_exists('orderBy', $filter)) {
//			switch($filter['orderBy']) {
//				default:
//				case 'name':
//					$qb->innerJoin(ArticleLangEntity::class, 'l');
//					$qb->orderBy('l.title', 'ASC');
//					break;
//				case 'date':
//					$qb->orderBy('a.createDate', 'ASC');
//					break;
//			}
//		}
//	}
//	
//	private function addPagerFilter($qb, $filter)
//	{
//		if(array_key_exists('offset', $filter) && array_key_exists('limit', $filter))
//		{
//			$qb->setFirstResult($filter['offset'])
//				->setMaxResults($filter['limit']);
//		}
//	}
	
	
	/**
	 * Add article
	 * 
	 * @param ArticleLangEntity $articleLangEntity		article lang entity
	 * @throws Exception\ArticleNotCreatedException
	 */
	public function create($articleLangEntity)
	{
		$create = $this->entityManager->persist($articleLangEntity->article);
		$this->entityManager->persist($articleLangEntity);
		$this->entityManager->flush();
		if (!$create) {
			throw new \Wame\Core\Exception\RepositoryException(_('Could not create the article'));
		}
		
		return $articleLangEntity->article;
	}
	
	
	/**
	 * Set article
	 * 
	 * @param int $articleId
	 * @param array $values
	 */
	public function update($articleLangEntity)
	{
		
		return $articleLangEntity->article;
	}
	
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

	/**
	 * Get article by criteria
	 * return article or exception
	 * 
	 * @param array $criteria
	 * @return ArticleEntity
	 * @throws RepositoryException
	 */
	public function getArticle($criteria)
	{
		$article = $this->get($criteria);
		
		if (!$article) {
			throw new RepositoryException(_('Article not found.'));
		}
		
		if ($article->status != self::STATUS_PUBLISHED) {
			throw new RepositoryException(_('Article is unpublished or removed.'));
		}
		
		if ($article->publishStartDate != null && strtotime($article->publishStartDate) < time()) {
			throw new RepositoryException(_('Article has not been published.'));
		}
		
		if ($article->publishEndDate != null && strtotime($article->publishEndDate) > time()) {
			throw new RepositoryException(_('Out of time of article publication.'));
		}
		
		return $article;
	}

	
	public function findFiltered($filterBuilder, $offset, $limit)
	{
		$allArticles = $this->find(['status' => ArticleRepository::STATUS_PUBLISHED]);

		$filterBuilder->setEntity(ArticleEntity::class);

		$filterBuilder->addFilter(new \Wame\FilterModule\Type\StatusFilter());
		
		$authorFilter = new \Wame\FilterModule\Type\AuthorFilter();
		$authorFilter->setItems($allArticles);
		$filterBuilder->addFilter($authorFilter);
		
		$dateFilter = new \Wame\FilterModule\Type\DateFilter();
		$dateFilter->setItems($allArticles);
		$filterBuilder->addFilter($dateFilter);
		
		$filterOrderBy = new \Wame\FilterModule\Type\OrderByFilter();
		$filterOrderBy
				->addOrder('name', 'title', ArticleLangEntity::class)
				->addOrder('id', 'id')
				->addOrder('date', 'createDate');
		$filterBuilder->addFilter($filterOrderBy);

		$filterBuilder->addFilter(new \Wame\FilterModule\Type\IdFilter());
		
		$this->setPaginator($filterBuilder->build()->count());
		
		// Page filter
		$filterPage = new \Wame\FilterModule\Type\PageFilter();
		$filterPage->setOffset($this->paginator->offset)
				->setLimit($this->paginator->itemsPerPage);
		$filterBuilder->addFilter($filterPage);
		
		return $filterBuilder->build()->get();
	}

}