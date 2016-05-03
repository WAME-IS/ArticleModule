<?php

namespace App\ArticleModule\Presenters;

use Wame\ArticleModule\Controls\Article;
use Wame\ArticleModule\Controls\ArticleList;
use Wame\ArticleModule\Controls\ArticleFilterControl;
use Wame\ArticleModule\Controls\ArticleArchiveControl;
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
	
	/** @var ArticleFilterControl @inject */
	public $articleFilterControl;
	
	/** @var ArticleArchiveControl @inject */
	public $articleArchiveControl;
	
	/** @var integer */
	protected $articleId;
	
	/** @var string */
	protected $articleSlug;
	
	
	/** @persistent */
    public $page;
	
	/** @persistent */
    public $sort;
	
	/** @persistent */
    public $filter = [];
	
	
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
		$componentArticleListControl->addComponent($this->createComponentArticleFilterControl(), 'articleFilter');
		$componentArticleListControl->addComponent($this->createComponentArticleArchiveControl(), 'articleArchive');
		$componentArticleListControl->setSortBy($sort);
		return $componentArticleListControl;
	}
	
	public function createComponentArticleFilterControl()
	{
		$componentArticleFilterControl = $this->articleFilterControl;
		return $componentArticleFilterControl;
	}
	
	public function createComponentArticleArchiveControl()
	{
		$componentArticleArchiveControl = $this->articleArchiveControl;
		return $componentArticleArchiveControl;
	}
}
