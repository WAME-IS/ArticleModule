<?php

namespace Wame\ArticleModule\Components;

interface IArticleNewsControlFactory
{
	/** @return ArticleNewsControls */
	public function create();	
}

class ArticleNewsControls extends ArticleListControl
{
	public function __construct(\Nette\Http\Request $httpRequest, \Wame\ArticleModule\Repositories\ArticleRepository $articleRepository, \Wame\FilterModule\IFilterBuilderFactory $filterBuilderFactory) {
		parent::__construct($httpRequest, $articleRepository, $filterBuilderFactory);
		
		$this->setPaginatorVisibility(false);
		$this->setFilterVisibility(false);
	}
}