<?php

namespace App\ArticleModule\Presenters;

use Wame\ArticleModule\Controls\ArticleControl;
use Wame\ArticleModule\Repositories\ArticleRepository;

class ArticlePresenter extends \App\Core\Presenters\BasePresenter
{
	/** @var ArticleRepository @inject */
	public $articleRepository;
	
	/** @var ArticleControl @inject */
	public $articleControl;
	
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
	
	public function createComponentArticleControl()
	{
		$articleId = $this->getParameter('id');
		$articleSlug = $this->getParameter('slug');
		
		$componentArticleControl = $this->articleControl;
		$componentArticleControl->setId($articleId);
		$componentArticleControl->setSlug($articleSlug);
		return $componentArticleControl;
	}

}
