<?php

namespace Wame\ArticleModule\Components;

use Wame\ArticleModule\Repositories\ArticleRepository;
use Wame\CategoryModule\Repositories\CategoryItemRepository;

//use Wame\FilterModule\Type\StatusFilter;
//use Wame\FilterModule\Type\AuthorFilter;
//use Wame\FilterModule\Type\DateFilter;
//use Wame\FilterModule\Type\OrderByFilter;
//use Wame\FilterModule\Type\IdFilter;
//
//use Wame\ArticleModule\Entities\ArticleEntity;
//use Wame\ArticleModule\Entities\ArticleLangEntity;

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
	
	/** @var limit */
	private $limit = null;
	
	/** @var integer */
	private $count = 5;
	
	/** @var integer */
	private $itemsPerPage = 10;
	
//	private $paginator;
	
//	/** @var integer */
//	private $paginatorOffset;
	
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
	
	private $category;
	
	
	public function __construct(\Nette\Http\Request $httpRequest, ArticleRepository $articleRepository, CategoryItemRepository $categoryItemRepository, \Wame\FilterModule\IFilterBuilderFactory $filterBuilderFactory) {
		parent::__construct();
		
		$this->articleRepository = $articleRepository;
		$this->categoryItemRepository = $categoryItemRepository;
		$this->lang = $this->articleRepository->lang;
		
		$this->filterBuilder = $filterBuilderFactory->create();
	}
	
	public function setPaginatorVisibility($visible)
	{
		$this->paginatorVisible = $visible;
	}
	
	public function setFilterVisibility($visible)
	{
		$this->filterVisible = $visible;
	}
	
//	public function __construct(\Wame\FilterModule\FilterBuilder $filterBuilder) {
//		$this->filterBuilder = $filterBuilder;
//	}
	
	/**
	 * Set items per page
	 * 
	 * @param type $itemsPerPage
	 */
	public function setItemsPerPage($itemsPerPage)
	{
		$this->itemsPerPage = $itemsPerPage;
	}
	
	public function setLimit($limit)
	{
		$this->limit = $limit;
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
	
	public function setCategory($category)
	{
		$this->category = $category;
	}
	
	public function setFilters()
	{
		
	}
	
//	// TODO: presunut do repository
//	private function getArticles()
//	{
//		$allArticles = $this->articleRepository->find(['status' => ArticleRepository::STATUS_PUBLISHED]);
//		
//		$filterBuilder = $this->filterBuilder;
//		$filterBuilder->setEntity(ArticleEntity::class);
//
//		$filterBuilder->addFilter(new StatusFilter());
//		
//		$authorFilter = new AuthorFilter();
//		$authorFilter->setItems($allArticles);
//		$filterBuilder->addFilter($authorFilter);
//		
//		$dateFilter = new DateFilter();
//		$dateFilter->setItems($allArticles);
//		$filterBuilder->addFilter($dateFilter);
//		
////		$filterBuilder->addFilter(new DateFilter());
//		
//		$filterOrderBy = new OrderByFilter();
//		$filterOrderBy
//				->addOrder('name', 'title', ArticleLangEntity::class)
//				->addOrder('id', 'id')
//				->addOrder('date', 'createDate');
//		$filterBuilder->addFilter($filterOrderBy);
//
//		$filterBuilder->addFilter(new IdFilter());
//		
//		$this->setPaginator($filterBuilder->build()->count());
//		
//		// Page filter
//		$filterPage = new \Wame\FilterModule\Type\PageFilter();
//		$filterPage->setOffset($this->paginator->offset)
//				->setLimit($this->paginator->itemsPerPage);
//		$filterBuilder->addFilter($filterPage);
//		
//		return $filterBuilder->build()->get();
//	}
	
//	private function setPaginator($itemCount)
//	{
//		$vp = $this['paginator'];
//		$vp->setCount($this->count);
//		$this->paginator = $vp->getPaginator();
//		$this->paginator->itemsPerPage = $this->itemsPerPage;
////		$this->paginatorOffset = $this->paginator->offset;
//		
//		$this->paginator->itemCount = $itemCount;
//	}

	protected function createComponentPaginator()
	{
		return new \Wame\Utils\Pagination;
	}
	
	protected function createComponentFilter()
	{
		$filterControl = new \Wame\FilterModule\Controls\FilterControl($this->filterBuilder);
		return $filterControl;
	}
	
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
		dump($this);
		
		$component = $this->componentInPosition->component;
		
		$sort = $this->componentInPosition->component->getParameter('sort');
		$order = $this->componentInPosition->component->getParameter('order');
		$limit = $this->componentInPosition->component->getParameter('limit');
		
		$categories = $this->categoryItemRepository->getCategories('component', $this->componentInPosition->component->id);
		
		$articles = $this->articleRepository->find();
		
		$paginator = $this['paginator'];
		$paginator->setCount($this->count);
		
		$paginator->getPaginator()->itemsPerPage = $this->itemsPerPage;
		
		$articles = $this->articleRepository->findFiltered($this->filterBuilder, $paginator);
		
//		$articles = $this->articleRepository->find();
		
		$this->template->paginatorVisible = $this->paginatorVisible;
		$this->template->filterVisible = $this->filterVisible;
		$this->template->articles = $articles;
		$this->template->paginatorOffset = $paginator->getPaginator()->offset;
		
		$this->getTemplateFile();
		$this->template->render();
	}
}