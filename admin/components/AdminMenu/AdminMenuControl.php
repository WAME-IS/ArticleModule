<?php

namespace App\ArticleModule\Presenters\AdminMenu;

class AdminMenuControl
{	
	public $item;

	/** @var \Nette\Application\LinkGenerator */
	private $linkGenerator;
	
	public function injectAnotherService(\Nette\Application\LinkGenerator $linkGenerator)
    {
        $this->linkGenerator = $linkGenerator;
    }
	
	public function __construct()
	{	
//		$this->linkGenerator = $linkGenerator;
		
		$this->item = $this->addItem();
	}
	
	private function addItem()
	{
		$item = new \App\AdminModule\Components\AdminMenu\Item();
		$item->setTitle(_('Články'));
//		$item->setLink($this->linkGenerator->link('Admin:Users:', ['id' => null]));
		$item->setIcon('fa fa-document');
		
		return $item->getItem();
	}
	
	private function addItemUserAdd()
	{
		$item = new \App\AdminModule\Components\AdminMenu\Item();
		$item->setTitle(_('Pridať užívateľa'));
//		$item->setLink($this->linkGenerator->link('Admin:User:add', ['id' => null]));
		$item->setIcon('fa fa-user-plus');
		
		return $item->getItem();
	}

}
