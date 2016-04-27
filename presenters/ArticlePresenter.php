<?php

namespace App\ArticleModule\Presenters;

use Wame\ArticleModule\Controls\Article;
use Wame\ArticleModule\Controls\ArticleList;
use Wame\ArticleModule\Controls\ArticleFilterControl;
use Wame\ArticleModule\Repositories\ArticleRepository;

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
	
	/** @var integer */
	protected $articleId;
	
	/** @var string */
	protected $articleSlug;
	
	private $articles;
	
	
	public function renderDefault()
	{
		
	}
	
	public function actionShow($id) {
		$this->articleId = $id;
	}
	
	public function createComponentArticle()
	{
		$articleId = $this->getParameter('id');
		$articleSlug = $this->getParameter('slug');
		
		$componentArticleControl = $this->articleControl;
		$componentArticleControl->setId($articleId);
		$componentArticleControl->setSlug($articleSlug);
		return $componentArticleControl;
	}
	
	public function createComponentArticleList()
	{
		$componentArticleListControl = $this->articleListControl;
		$componentArticleListControl->addComponent($this->createComponentArticle(), 'article');
		return $componentArticleListControl;
	}
	
	public function createComponentArticleFilterControl()
	{
		$componentArticleFilterControl = $this->articleFilterControl;
		return $componentArticleFilterControl;
	}

}
