<?php

namespace Wame\ArticleModule\Controls;

use Wame\ArticleModule\Repositories\ArticleRepository;

class ArticleList extends BaseControl
{	
	const SORT_BY_NAME = 'name';
	const SORT_BY_DATE = 'date';
	const SORT_ASC = 'ASC';
	const SORT_DESC = 'DESC';
	
	/** @var integer */
	private $count = 5;
	
	/** @var integer */
	private $itemsPerPage = 2;
	
	/** @var integer */
	private $paginatorOffset;
	
	/** @var array */
	private $orderBy = [];
	
	
	/**
	 * Set items per page
	 * 
	 * @param type $itemsPerPage
	 */
	public function setItemsPerPage($itemsPerPage)
	{
		$this->itemsPerPage = $itemsPerPage;
	}
	
	/**
	 * Set count of items in paginator
	 * @param type $count
	 */
	public function setCount($count)
	{
		$this->count = $count;
	}
	
	// TODO: presunut do oddelenej casti, aby sortovanie mohlo byt vyuzivane viacerymi komponentami
	/**
	 * Set SortBy
	 * 
	 * @param type $name
	 * @param type $sort
	 */
	public function setSortBy($name, $sort = self::SORT_ASC)
	{
		$this->orderBy = $name;
		
//		switch($name) {
//			default:
//			case self::SORT_BY_NAME:
//				$newName = 'langs.title';
//				break;
//			case self::SORT_BY_DATE:
//				$newName = 'publishStartDate';
//				break;
//		}
//		
//		if($sort == self::SORT_ASC || $sort == self::SORT_DESC) {
////			$this->orderBy[$newName] = $sort;
//			$this->orderBy = [$newName, $sort];
//		}
	}
	
	/**
	 * Get articles
	 * 
	 * @return array	articles
	 */
	private function getArticles()
	{
//		dump($this->presenter->getParameter('filter'));
		
		$filter = [];
		$filter['status'] = ArticleRepository::STATUS_PUBLISHED;
		
		$filterParam = $this->presenter->getParameter('filter');
		
		if($filterParam) {
			$filter['year'] = $filterParam['y'];
			$filter['month'] = $filterParam['m'];
		}
		
		$filter['orderBy'] = $this->orderBy;
		
		$vp = $this['paginator'];
		$vp->setCount($this->count);
		$paginator = $vp->getPaginator();
		$paginator->itemsPerPage = $this->itemsPerPage;
		$this->paginatorOffset = $paginator->offset;
		$paginator->itemCount = $this->articleRepository->countByFilter($filter);
//		$paginator->itemCount = $this->articleRepository->countBy();
		
		
		$filter['limit'] = $paginator->itemsPerPage;
		$filter['offset'] = $paginator->offset;

//		return $this->articleRepository->find([
//				'status' => ArticleRepository::STATUS_PUBLISHED
//			], 
//			$this->orderBy, 
//			$paginator->itemsPerPage, 
//			$paginator->offset
//		);
		
		return $this->articleRepository->findByFilter($filter);
	}
	
	
	protected function createComponentPaginator()
	{
		return new \Wame\Utils\Pagination;
	}
	
	/**
	 * Render
	 */
	public function render()
	{
		$this->setTemplate('article_list');
		
		$articles = $this->getArticles();
		
		$this->template->articles = $articles;
		$this->template->paginatorOffset = $this->paginatorOffset;
		$this->template->render();
	}
}