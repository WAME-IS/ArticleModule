<?php

namespace App\ArticleModule\Presenters;

class ArticlesPresenter extends \App\Presenters\BasePresenter
{
	/** @var \App\ArticleModule\Model\Article @inject */
	public $article;
	
	public function renderDefault()
	{
		$this->template->anyVariable = 'any value';
		$this->template->time = $this->article->getTime();
	}

}
