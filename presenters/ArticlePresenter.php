<?php

namespace App\ArticleModule\Presenters;

use Wame\ArticleModule\Controls\ArticleShow;

class ArticlePresenter extends \App\Core\Presenters\BasePresenter
{
	/** @var ArticleShow @inject */
	public $articleShow;
	
	/** @var integer */
	protected $articleId;
	
	public function renderDefault()
	{
	}
	
	public function actionShow($id) {
		$this->articleId = $id;
	}
	
	public function createComponentArticleShow()
	{
		$componentArticleShow = $this->articleShow;
		$componentArticleShow->setId($this->articleId);
		return $componentArticleShow;
	}

}
