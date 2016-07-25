<?php

namespace Wame\ArticleModule\Components;

use Wame\ArticleModule\Repositories\ArticleRepository;
use Wame\CategoryModule\Repositories\CategoryItemRepository;

use \Wame\FilterModule2\FilterBuilder;

use Wame\ArticleModule\Components\IArticleControlFactory;


interface IArticleListControlFactory
{
	/** @return ArticleListControl */
	public function create();	
}


class ArticleListControl extends \Wame\Core\Components\BaseControl
{	
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
	
	/** @var boolean */
	private $sortVisible = true;
	
	/** @var integer */
	private $category;
	
	/** @var string */
	private $sort;
	
	private $page = 0;
    
    /** @var ArticleEntity[] */
    private $article;
    
    private $IArticleControlFactory;
	
	
	public function __construct(
            \Nette\DI\Container $container, 
            ArticleRepository $articleRepository, 
            CategoryItemRepository $categoryItemRepository, 
            \Wame\FilterModule\IFilterBuilderFactory $filterBuilderFactory, 
            \Wame\FilterModule2\IFilterBuilderFactory $filterBuilderFactory2,
            IArticleControlFactory $IArticleControlFactory
    ) {
		parent::__construct($container);
		
		$this->articleRepository = $articleRepository;
		$this->categoryItemRepository = $categoryItemRepository;
		$this->lang = $this->articleRepository->lang;
		
//		$this->filterBuilder = $filterBuilderFactory->create();
		$this->filterBuilder = $filterBuilderFactory2->create();
        
        $this->IArticleControlFactory = $IArticleControlFactory;
	}
	
	
	/**
	 * Set paginator visibility
	 * 
	 * @param boolean $visible	visible
	 */
	public function setPaginatorVisibility($visible)
	{
		$this->paginatorVisible = $visible;
	}
	
	/**
	 * Set filter visibility
	 * 
	 * @param boolean $visible	visible
	 */
	public function setFilterVisibility($visible)
	{
		$this->filterVisible = $visible;
	}
	
	/**
	 * Set sort visibility
	 * 
	 * @param boolean $visible	visible
	 */
	public function setSortVisibility($visible)
	{
		$this->sortVisible = $visible;
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
	 * Render
	 */
	public function render()
	{
		$this->setComponent();
        
		$this->setPaginator($this->filterBuilder->getCountBeforeFilter());
		
		$this->template->paginatorVisible = $this->paginatorVisible;
		$this->template->filterVisible = $this->filterVisible;
		$this->template->articles = $this->getArticles();
		
		$this->getTemplateFile();
		$this->template->render();
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
	 * 
	 * @return \Wame\FilterModule\Controls\FilterControl
	 */
	protected function createComponentFilter()
	{
		$filterControl = new \Wame\FilterModule2\Controls\FilterControl($this->filterBuilder);
		return $filterControl;
	}
	
	/**
	 * Create Article component
	 * 
	 * @return \Wame\ArticleModule\Components\ArticleControl
	 */
	protected function createComponentArticle()
	{
        $component = $this->IArticleControlFactory->create();
		$component->setInList(true);
		return $component;
	}
	
	/**
	 * Create Sort component
	 * 
	 * @return \Wame\FilterModule2\Controls\SortControl
	 */
	protected function createComponentSort()
	{
		$component = new \Wame\FilterModule2\Controls\SortControl();
		$component->setOrders([
			['name' => _('Name'), 'alias' => 'name', 'value' => 'langs.title'],
			['name' => _('Date'), 'alias' => 'date', 'value' => 'createDate']
		]);
		
		return $component;
	}
	
	
	/**
	 * Get articles
	 */
	private function getArticles() {
		$criteria = ['status' => ArticleRepository::STATUS_PUBLISHED];
		$orderBy = [$this['sort']->getOrder()['value'] => 'ASC'];
		
		$articles = $this->articleRepository->find($criteria, $orderBy);
		
		$limit = $this->itemsPerPage;
		$offset = $limit * $this->getPage();
		
		$filteredArticles = $this->filterBuilder
								->on($articles)
								->add(new \Wame\FilterModule2\Type\DateFilter())
								->add(new \Wame\FilterModule2\Type\AuthorFilter())
								->get($limit, $offset);
		
		return $filteredArticles;
	}
	
	private function getPage() {
		return ( (int)$this->presenter->getParameter('page') ?: 1 ) - 1;
	}
	
	/**
	 * Set paginator
	 */
	private function setPaginator($count)
	{
//		$doctrinePaginator = new \Doctrine\ORM\Tools\Pagination\Paginator();
		
		$this->paginator = $this['paginator'];
		$this->paginator->setCount($this->count);
		$this->paginator->getPaginator()->itemsPerPage = $this->itemsPerPage;
		
		// Paginator
		$this->paginator->getPaginator()->itemCount = $count;
		
		$this->template->paginatorOffset = $this->paginator->getPaginator()->offset;
	}
	
	
	
//	/**
//	 * Get articles
//	 * 
//	 * @return type
//	 */
//	private function getArticles()
//	{
////		$categories = $this->categoryItemRepository->getCategories('component', $this->componentInPosition->component->id);
//		
//		$criteria = [];
//		$orderBy = ['langs.title' => 'ASC'];
//		
//		$articles = $articleRepository->find($criteria, $orderBy);
//		
//		return $this->getFilteredArticles($articles);
//		
//		
////		$this->setFilter();
////		
////		return $this->articleRepository->findFiltered($this->filterBuilder, $this->paginator);
//	}
//	
//	private function getFilteredArticles($articles)
//	{
//		$filterBuilder2 = $filterBuilderFactory2->create();
//		$filteredArticles = $filterBuilder2
//								->on($articles)
//								->add(new \Wame\FilterModule2\Type\DateFilter())
//								->add(new \Wame\FilterModule2\Type\AuthorFilter())
//								->get($this->paginator->getPaginator()->itemsPerPage, $this->paginator->getPaginator()->offset);
//		
//		return $filteredArticles;
//	}
	
//	/**
//	 * Set filer
//	 */
//	private function setFilter()
//	{
//		$this->filterBuilder->setEntity(\Wame\ArticleModule\Entities\ArticleEntity::class);
//		$filterOrderBy = new \Wame\FilterModule\Type\OrderByFilter();
//		$filterOrderBy
//				->addOrder('name', 'title', \Wame\ArticleModule\Entities\ArticleLangEntity::class)
//				->addOrder('date', 'createDate');
//		
//		if($this->orderBy) {
//			$filterOrderBy->setBy($this->orderBy);
//		}
//		if($this->sort) {
//			$filterOrderBy->setSort($this->orderBy);
//		}
//		
//		$this->filterBuilder->addFilter($filterOrderBy);
//	}
	
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
			$this->sortVisible = $this->getComponentParameter('sort_visible');
		}
	}
    
}