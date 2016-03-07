<?php

namespace App\ArticleModule\Presenters;

class ArticlesPresenter extends \App\Presenters\BasePresenter
{
	public function renderDefault()
	{
		$this->template->anyVariable = 'any value';
	}

}
