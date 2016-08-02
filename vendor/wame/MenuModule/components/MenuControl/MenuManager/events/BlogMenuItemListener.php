<?php

namespace Wame\ArticleModule\Vendor\Wame\MenuModule\Components\MenuManager\Events;

use Nette\Object;
use Wame\MenuModule\Repositories\MenuRepository;

class BlogMenuItemListener extends Object 
{
    const TYPE = 'blog';
    
	/** @var MenuRepository */
	private $menuRepository;
	
	/** @var string */
	private $lang;
	
	
	public function __construct(
		MenuRepository $menuRepository
	) {
		$this->menuRepository = $menuRepository;
		$this->lang = $menuRepository->lang;
		
		$menuRepository->onCreate[] = [$this, 'onCreate'];
		$menuRepository->onUpdate[] = [$this, 'onUpdate'];
		$menuRepository->onDelete[] = [$this, 'onDelete'];
	}

	
	public function onCreate($form, $values, $menuEntity) 
	{
		if ($menuEntity->type == self::TYPE) {
			$menuEntity->langs[$this->lang]->setTitle($values['title']);
		}
	}
	
	
	public function onUpdate($form, $values, $menuEntity)
	{
		if ($menuEntity->type == self::TYPE) {
			$menuEntity->langs[$this->lang]->setTitle($values['title']);
		}
	}
	
	
	public function onDelete()
	{
		
	}

}
