<?php

namespace Wame\ArticleModule\Controls;

use Wame\ArticleModule\Repositories\ArticleRepository;

use Wame\FilterModule\Type\StatusFilter;
use Wame\FilterModule\Type\AuthorFilter;
use Wame\FilterModule\Type\DateFilter;
use Wame\FilterModule\Type\OrderByFilter;

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
	
	private $filterBuilder;
	
	
	public function __construct(\Wame\FilterModule\FilterBuilder $filterBuilder) {
		$this->filterBuilder = $filterBuilder;
	}
	
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
	
	private function getArticles()
	{
		$filterBuilder = clone $this->filterBuilder;
		$filterBuilder->setEntity(\Wame\ArticleModule\Entities\ArticleEntity::class);

		$filterBuilder->addFilter(new StatusFilter(ArticleRepository::STATUS_PUBLISHED));
		$filterBuilder->addFilter(new AuthorFilter());
		$filterBuilder->addFilter(new DateFilter());
		
		$filterOrderBy = new OrderByFilter();
		$filterOrderBy->addOrder('name', 'title', \Wame\ArticleModule\Entities\ArticleLangEntity::class);
		$filterOrderBy->addOrder('date', 'createDate');
		$filterBuilder->addFilter($filterOrderBy);
		
		$vp = $this['paginator'];
		$vp->setCount($this->count);
		$paginator = $vp->getPaginator();
		$paginator->itemsPerPage = $this->itemsPerPage;
		$this->paginatorOffset = $paginator->offset;
		
		$paginator->itemCount = $filterBuilder->build()->count();
		
		// Page filter
		$filterPage = new \Wame\FilterModule\Type\PageFilter();
		$filterPage->setOffset($paginator->offset);
		$filterPage->setLimit($paginator->itemsPerPage);
		$filterBuilder->addFilter($filterPage);
		
		return $filterBuilder->build()->get();
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