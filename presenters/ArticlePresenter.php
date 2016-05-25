<?php

namespace App\ArticleModule\Presenters;

//use Wame\ArticleModule\Controls\Article;

use Wame\ArticleModule\Components\IArticleControlFactory;
use Wame\ArticleModule\Components\IArticleListControlFactory;
use Wame\ArticleModule\Components\IArticleNewsControlFactory;

use Wame\FilterModule\Controls\SortControl;
use Wame\FilterModule\Controls\FilterDateControl;
use Wame\FilterModule\Controls\FilterAuthorControl;
use Wame\FilterModule\Controls\FilterCheckboxControl;
use Wame\ArticleModule\Repositories\ArticleRepository;

use Wame\CategoryModule\Components\ICategoryListControlFactory;

use Wame\TagModule\Controls\TagListControl;

use Wame\HeadControl\MetaTitle;
use Wame\HeadControl\MetaDescription;

class ArticlePresenter extends \App\Core\Presenters\BasePresenter
{
	/** @var ArticleRepository @inject */
	public $articleRepository;
	
	/** @var IArticleControlFactory @inject */
	public $IArticleControlFactory;
	
	/** @var IArticleListControlFactory @inject */
	public $IArticleListControlFactory;
	
	/** @var IArticleNewsControlFactory @inject */
	public $IArticleNewsControlFactory;
	
	/** @var SortControl @inject */
	public $sortControl;
	
//	/** @var FilterDateControl @inject */
//	public $filterDateControl;
//	
//	/** @var FilterAuthorControl @inject */
//	public $filterAuthorControl;
//	
//	/** @var FilterCheckboxControl @inject */
//	public $filterCheckboxControl;
	
	/** @var TagListControl @inject */
	public $tagListControl;
	
	/** @var ICategoryListControlFactory @inject */
	public $ICategoryListControlFactory;
	
	/** @var integer */
	protected $articleId;
	
	/** @var string */
	protected $articleSlug;
	
	private $filterBuilder;
	
	
	/** @persistent */
    public $page;
	
	/** @persistent */
    public $orderBy;
	
	/** @persistent */
    public $sort;
	
	/** @persistent */
    public $year;
	
	/** @persistent */
    public $month;
	
	/** @persistent */
    public $author;
	
	
	public function __construct(\Wame\FilterModule\IFilterBuilderFactory $filterBuilderFactory, \Wame\ArticleModule\Repositories\ArticleRepository $articleRepository) {
		parent::__construct();
		
		$this->filterBuilder = $filterBuilderFactory->create();
	}
	
	public function renderDefault()
	{
		
	}
	
	public function actionShow($id) {
		$this->articleId = $id;
		
		$article = $this->articleRepository->get(['id' => $this->articleId]);
		
		
		$title = $article->langs[$this->lang]->title;
		$description = $article->langs[$this->lang]->description;
		
		$component = $this->headControl;
		$component->getType(new MetaTitle)->setContent($title);
		$component->getType(new MetaDescription)->setContent($description);
		
		$this->articleRepository->onRead($id);
	}
	
	public function createComponentArticle()
	{
		$articleId = $this->getParameter('id');
		$articleSlug = $this->getParameter('slug');
		
		$component = $this->IArticleControlFactory->create();
		$component->setId($articleId);
		$component->setSlug($articleSlug);
		
		$component->addComponent($this->createComponentTagList(), 'tagList');
		$component->addComponent($this->createComponentCategoryList(), 'categoryList');
		
		return $component;
	}
	
	public function createComponentArticleList()
	{
		$sort = $this->sort;
		
		$component = $this->IArticleListControlFactory->create();
		
		
		
		
//		$componentArticleListControl = $this->articleListControl;
		
		$articleComponent = $this->createComponentArticle();
		$articleComponent->setInList(true);
		
//		$component->addComponent($articleComponent, 'article');
		$component->addComponent($this->createComponentSortControl(), 'sort');
//		$component->addComponent($this->createComponentFilterDateControl(), 'filterDate');
		$component->setSortBy($sort);
		return $component;
	}
	
	public function createComponentArticleNews()
	{
		$component = $this->IArticleNewsControlFactory->create();
		
		return $component;
	}
	
	public function createComponentSortControl()
	{
		$sortControl = $this->sortControl;
		return $sortControl;
	}
	
	public function createComponentTagList()
	{
		$control = $this->tagListControl;
		return $control;
	}
	
	public function createComponentCategoryList()
	{
		$control = $this->ICategoryListControlFactory->create();
		return $control;
	}
	
//	public function createComponentFilterDateControl()
//	{
//		$articles = $this->articleRepository->find([
//			'status' => ArticleRepository::STATUS_PUBLISHED
//		]);
//		
//		$filterDateControl = $this->filterDateControl;
//		$filterDateControl->setItems($articles);
//		return $filterDateControl;
//	}
}
