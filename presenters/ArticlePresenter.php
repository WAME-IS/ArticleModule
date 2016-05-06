<?php

namespace App\ArticleModule\Presenters;

use Wame\ArticleModule\Controls\Article;
use Wame\ArticleModule\Controls\ArticleList;
use Wame\FilterModule\Controls\SortControl;
use Wame\FilterModule\Controls\FilterDateControl;
use Wame\FilterModule\Controls\FilterAuthorControl;
use Wame\FilterModule\Controls\FilterCheckboxControl;
use Wame\ArticleModule\Repositories\ArticleRepository;

use Wame\HeadControl\MetaTitle;
use Wame\HeadControl\MetaDescription;

class ArticlePresenter extends \App\Core\Presenters\BasePresenter
{
	/** @var ArticleRepository @inject */
	public $articleRepository;
	
	/** @var Article @inject */
	public $articleControl;
	
	/** @var ArticleList @inject */
	public $articleListControl;
	
	/** @var SortControl @inject */
	public $sortControl;
	
	/** @var FilterDateControl @inject */
	public $filterDateControl;
	
	/** @var FilterAuthorControl @inject */
	public $filterAuthorControl;
	
	/** @var FilterCheckboxControl @inject */
	public $filterCheckboxControl;
	
	/** @var integer */
	protected $articleId;
	
	/** @var string */
	protected $articleSlug;
	
	
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
		
		$componentArticleControl = $this->articleControl;
//		$componentArticleControl->setInList($inList);
		$componentArticleControl->setId($articleId);
		$componentArticleControl->setSlug($articleSlug);
		return $componentArticleControl;
	}
	
	public function createComponentArticleList()
	{
		$sort = $this->sort;
		
		$componentArticleListControl = $this->articleListControl;
		
		$articleComponent = $this->createComponentArticle();
		$articleComponent->setInList(true);
		
		$componentArticleListControl->addComponent($articleComponent, 'article');
		$componentArticleListControl->addComponent($this->createComponentSortControl(), 'sort');
		$componentArticleListControl->addComponent($this->createComponentFilterDateControl(), 'filterDate');
		$componentArticleListControl->addComponent($this->createComponentFilterAuthorControl(), 'filterAuthor');
		$componentArticleListControl->addComponent($this->createComponentFilterCheckboxControl(), 'filterCheckbox');
		$componentArticleListControl->setSortBy($sort);
		return $componentArticleListControl;
	}
	
	public function createComponentSortControl()
	{
		$sortControl = $this->sortControl;
		return $sortControl;
	}
	
	public function createComponentFilterDateControl()
	{
		$articles = $this->articleRepository->find([
			'status' => ArticleRepository::STATUS_PUBLISHED
		]);
		
		$filterDateControl = $this->filterDateControl;
		$filterDateControl->setItems($articles);
		return $filterDateControl;
	}
	
	public function createComponentFilterAuthorControl()
	{
		$articles = $this->articleRepository->find([
			'status' => ArticleRepository::STATUS_PUBLISHED
		]);
		
		$filterAuthorControl = $this->filterAuthorControl;
		$filterAuthorControl->setItems($articles);
		
		return $filterAuthorControl;
	}
	
	public function createComponentFilterCheckboxControl()
	{
		$articles = $this->articleRepository->find([
			'status' => ArticleRepository::STATUS_PUBLISHED
		]);
		
		$filterCheckboxControl = $this->filterCheckboxControl;
		$filterCheckboxControl->setItems($articles);
		$filterCheckboxControl->setSelect('id', 'nick', 'createUser');
		$filterCheckboxControl->setTitle('Authors');
		$filterCheckboxControl->setName('author');
		
		return $filterCheckboxControl;
	}
}
