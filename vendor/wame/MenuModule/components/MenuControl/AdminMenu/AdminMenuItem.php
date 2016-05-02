<?php

namespace Wame\ArticleModule\Vendor\Wame\MenuModule\Components\MenuControl\AdminMenu;

use Wame\MenuModule\Models\Item;

class AdminMenuItem
{	
    /** @var \Nette\Application\LinkGenerator */
	private $linkGenerator;
	
	public function __construct($linkGenerator)
	{
		$this->linkGenerator = $linkGenerator;
	}
    
	public function addItem()
	{
		$item = new Item();
		$item->setName('article');
		$item->setPriority(10);
		$item->setTitle(_('Articles'));
		$item->setLink($this->linkGenerator->link('Admin:Article:', ['id' => null]));
		$item->setIcon('fa fa-file-text');
		
		$item->addNode($this->articlesDefault());
		
		return $item->getItem();
	}
	
	private function articlesDefault()
	{
		$item = new Item();
		$item->setName('article-articles');
		$item->setTitle(_('Articles'));
		$item->setLink($this->linkGenerator->link('Admin:Article:', ['id' => null]));
		
		return $item->getItem();
	}

}
