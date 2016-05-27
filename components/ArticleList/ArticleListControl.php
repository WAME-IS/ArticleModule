<?php

namespace Wame\ArticleModule\Components;

use Wame\ArticleModule\Repositories\ArticleRepository;
use Wame\CategoryModule\Repositories\CategoryItemRepository;

use \Wame\FilterModule\FilterBuilder;


interface IArticleListControlFactory
{
	/** @return ArticleListControl */
	public function create();	
}


class ArticleListControl extends \Wame\Core\Components\BaseControl
{	
	const SORT_BY_NAME = 'name';
	const SORT_BY_DATE = 'date';
	const SORT_ASC = 'ASC';
	const SORT_DESC = 'DESC';
	
	/** @var integer */
	private $count = 5;
	
	/** @var integer */
	private $itemsPerPage = 10;
	
	/** @var Paginator */
	private $paginator;
	
	/** @var array */
	private $orderBy = [];
	
	/** @var FilterBuilder */
	private $filterBuilder;
	
	/** @var ArticleRepository */
	public $articleRepository;
	
	/** @var CategoryItemRepository */
	public $categoryItemRepository;
	
	/** @var string */
	private $lang;
	
	/** @var boolean */
	private $paginatorVisible = true;
	
	/** @var boolean */
	private $filterVisible = true;
	
	/** @var integer */
	private $category;
	
	/** @var string */
	private $sort;
	
	
	public function __construct(ArticleRepository $articleRepository, CategoryItemRepository $categoryItemRepository, \Wame\FilterModule\IFilterBuilderFactory $filterBuilderFactory) {
		parent::__construct();
		
		$this->articleRepository = $articleRepository;
		$this->categoryItemRepository = $categoryItemRepository;
		$this->lang = $this->articleRepository->lang;
		
		$this->filterBuilder = $filterBuilderFactory->create();
	}
	
	
	/**
	 * Set paginator visibility
	 * 
	 * @param type $visible
	 */
	public function setPaginatorVisibility($visible)
	{
		$this->paginatorVisible = $visible;
	}
	
	/**
	 * Set filter visibility
	 * 
	 * @param type $visible
	 */
	public function setFilterVisibility($visible)
	{
		$this->filterVisible = $visible;
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
	
	/**
	 * Set SortBy
	 * 
	 * @param type $name
	 * @param type $sort
	 */
	public function setSortBy($name)
	{
		$this->orderBy = $name;
	}
	
	/**
	 * Set category
	 * 
	 * @param type $category
	 */
	public function setCategory($category)
	{
		$this->category = $category;
	}
	
	/**
	 * Set filters
	 */
	public function setFilters()
	{
		
	}
	
	/**
	 * Create Paginator component
	 * 
	 * @return \Wame\Utils\Pagination
	 */
	protected function createComponentPaginator()
	{
		return new \Wame\Utils\Pagination;
	}
	
	/**
	 * Create Filter component
	 * @return \Wame\FilterModule\Controls\FilterControl
	 */
	protected function createComponentFilter()
	{
		$filterControl = new \Wame\FilterModule\Controls\FilterControl($this->filterBuilder);
		return $filterControl;
	}
	
	/**
	 * Create Article component
	 * @return \Wame\ArticleModule\Components\ArticleControl
	 */
	protected function createComponentArticle()
	{
		$component = new \Wame\ArticleModule\Components\ArticleControl($this->articleRepository);
		$component->setInList(true);
		return $component;
	}
	
	/**
	 * Render
	 */
	public function render()
	{
		$this->setComponent();
		
		$this->setPaginator();
		
		$articles = $this->getArticles();
		
		$this->template->paginatorVisible = $this->paginatorVisible;
		$this->template->filterVisible = $this->filterVisible;
		$this->template->articles = $articles;
		
		
		$this->getTemplateFile();
		$this->template->render();
	}
	
	/**
	 * Set paginator
	 */
	private function setPaginator()
	{
		$this->paginator = $this['paginator'];
		$this->paginator->setCount($this->count);
		$this->paginator->getPaginator()->itemsPerPage = $this->itemsPerPage;
		
		$this->template->paginatorOffset = $this->paginator->getPaginator()->offset;
	}
	
	/**
	 * Get articles
	 * 
	 * @return type
	 */
	private function getArticles()
	{
//		$categories = $this->categoryItemRepository->getCategories('component', $this->componentInPosition->component->id);
//		$articles = $this->articleRepository->find();
		
		$this->setFilter();
		
		return $this->articleRepository->findFiltered($this->filterBuilder, $this->paginator);
	}
	
	/**
	 * Set filer
	 */
	private function setFilter()
	{
		$this->filterBuilder->setEntity(\Wame\ArticleModule\Entities\ArticleEntity::class);
		$filterOrderBy = new \Wame\FilterModule\Type\OrderByFilter();
		$filterOrderBy
				->addOrder('name', 'title', \Wame\ArticleModule\Entities\ArticleLangEntity::class)
				->addOrder('date', 'createDate');
		
		if($this->orderBy) {
			$filterOrderBy->setBy($this->orderBy);
		}
		if($this->sort) {
			$filterOrderBy->setSort($this->orderBy);
		}
		
		$this->filterBuilder->addFilter($filterOrderBy);
	}
	
	/**
	 * Set component
	 */
	private function setComponent()
	{
		if($this->componentInPosition) {
			$this->sort = $this->getComponentParameter('sort');
			$this->orderBy = $this->getComponentParameter('order');
			$this->itemsPerPage = $this->getComponentParameter('limit');
			$this->paginatorVisible = $this->getComponentParameter('paginator_visible');
			$this->filterVisible = $this->getComponentParameter('filter_visible');
		}
		
	}
	
	/**
	 * Get component parameter
	 * 
	 * @param string $parameter		parameter name
	 * @return mixin				parameter
	 */
	private function getComponentParameter($parameter)
	{
		return $this->componentInPosition->component->getParameter($parameter);
	}
}