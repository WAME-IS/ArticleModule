<?php

namespace App\ArticleModule\Admin\Extensions\AdminModule\Components;

class AdminMenuControl
{	
	/** @var \Nette\Application\LinkGenerator */
	private $linkGenerator;
	
	public function __construct($linkGenerator)
	{
		$this->linkGenerator = $linkGenerator;
	}
	
	public function addItem()
	{
		$item = new \App\AdminModule\Components\AdminMenu\Item();
		$item->setTitle(_('Články'));
		$item->setLink('#');
		$item->setIcon('fa fa-file-text');
		
		$item->addChild($this->articleCategoriesDefault());
		$item->addChild($this->articleCategoryDefault());
		$item->addSeparator();
		$item->addChild($this->articlesDefault());
		$item->addChild($this->articleDefault());
		
		return $item->getItem();
	}
	
	private function articleCategoriesDefault()
	{
		$item = new \App\AdminModule\Components\AdminMenu\Item();
		$item->setTitle(_('Všetky kategórie'));
		$item->setLink($this->linkGenerator->link('Admin:ArticleCategories:', ['id' => null]));
		
		return $item->getItem();
	}
	
	private function articleCategoryDefault()
	{
		$item = new \App\AdminModule\Components\AdminMenu\Item();
		$item->setTitle(_('Pridať kategóriu'));
		$item->setLink($this->linkGenerator->link('Admin:ArticleCategory:', ['id' => null]));
		
		return $item->getItem();
	}
	
	private function articlesDefault()
	{
		$item = new \App\AdminModule\Components\AdminMenu\Item();
		$item->setTitle(_('Všetky články'));
		$item->setLink($this->linkGenerator->link('Admin:Articles:', ['id' => null]));
		
		return $item->getItem();
	}
	
	private function articleDefault()
	{
		$item = new \App\AdminModule\Components\AdminMenu\Item();
		$item->setTitle(_('Pridať článok'));
		$item->setLink($this->linkGenerator->link('Admin:Article:', ['id' => null]));
		
		return $item->getItem();
	}

}
