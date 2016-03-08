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
		$item->setTitle(_('Články'));
		$item->setLink($this->linkGenerator->link('Admin:Articles:', ['id' => null]));
		$item->setIcon('fa fa-file-text');
		
		$item->addNode($this->articleCategoriesDefault());
		$item->addNode($this->articleCategoryDefault());
		$item->addNode($this->articlesDefault());
		$item->addNode($this->articleDefault());
		
		return $item->getItem();
	}
	
	private function articleCategoriesDefault()
	{
		$item = new Item();
		$item->setTitle(_('Všetky kategórie'));
		$item->setLink($this->linkGenerator->link('Admin:ArticleCategories:', ['id' => null]));
		
		return $item->getItem();
	}
	
	private function articleCategoryDefault()
	{
		$item = new Item();
		$item->setTitle(_('Pridať kategóriu'));
		$item->setLink($this->linkGenerator->link('Admin:ArticleCategory:', ['id' => null]));
		
		return $item->getItem();
	}
	
	private function articlesDefault()
	{
		$item = new Item();
		$item->setTitle(_('Všetky články'));
		$item->setLink($this->linkGenerator->link('Admin:Articles:', ['id' => null]));
		
		return $item->getItem();
	}
	
	private function articleDefault()
	{
		$item = new Item();
		$item->setTitle(_('Pridať článok'));
		$item->setLink($this->linkGenerator->link('Admin:Article:', ['id' => null]));
		
		return $item->getItem();
	}

}
