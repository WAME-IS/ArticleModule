<?php

namespace Wame\ArticleModule\Vendor\Wame\ComponentModule;

interface IArticleComponentFactory
{
	/** @return ArticleComponent */
	public function create();	
}


class ArticleComponent implements \Wame\MenuModule\Models\IMenuItem
{	
	/** @var \Nette\Application\LinkGenerator */
	private $linkGenerator;

	
	public function __construct(
		\Nette\Application\LinkGenerator $linkGenerator
	) {
		$this->linkGenerator = $linkGenerator;
	}
	
	
	public function addItem()
	{
		$item = new \Wame\MenuModule\Models\Item();
		$item->setName('article');
		$item->setTitle(_('Article'));
		$item->setLink($this->linkGenerator->link('Admin:Article:create', ['id' => null]));
		$item->setIcon('fa fa-file-text');
		
		return $item->getItem();
	}
	
}