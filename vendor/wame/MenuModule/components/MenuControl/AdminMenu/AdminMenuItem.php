<?php

namespace Wame\ArticleModule\Vendor\Wame\MenuModule\Components\MenuControl\AdminMenu;

use Wame\MenuModule\Models\Item;

class AdminMenuItem
{	
	public $name = 'article';

    /** @var \Nette\Application\LinkGenerator */
	private $linkGenerator;
	
	public function __construct($linkGenerator)
	{
		$this->linkGenerator = $linkGenerator;
	}
    
	public function addItem()
	{
		$item = new Item();
        $item->insertBefore('users');
		$item->setTitle(_('Articles'));
		$item->setLink($this->linkGenerator->link('Admin:Articles:', ['id' => null]));
		$item->setIcon('fa fa-file-text');

		$item->addNode($this->articlesDefault());
		$item->addNode($this->articleDefault());
		
		return $item->getItem();
	}
	
	private function articlesDefault()
	{
		$item = new Item();
		$item->setTitle(_('Articles'));
		$item->setLink($this->linkGenerator->link('Admin:Articles:', ['id' => null]));
		
		return $item->getItem();
	}
	
	private function articleDefault()
	{
		$item = new Item();
		$item->setTitle(_('Add article'));
		$item->setLink($this->linkGenerator->link('Admin:Article:', ['id' => null]));
		
		return $item->getItem();
	}

}
