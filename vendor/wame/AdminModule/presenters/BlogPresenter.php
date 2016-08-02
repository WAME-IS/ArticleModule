<?php

namespace App\AdminModule\Presenters;

use Wame\MenuModule\Forms\MenuItemForm;

class BlogPresenter extends \App\AdminModule\Presenters\BasePresenter
{
	/** @var MenuItemForm @inject */
	public $menuItemForm;
	
	/**
	 * Menu item form
	 * 
	 * @return MenuItemForm
	 */
	protected function createComponentBlogMenuItemForm()
	{
		$form = $this->menuItemForm
						->setActionForm('blogMenuItemForm')
						->setType('blog')
						->setId($this->id)
                        ->addFormContainer(new \Wame\ArticleModule\Vendor\Wame\MenuModule\Components\MenuManager\Forms\BlogFormContainer(), 'BlogFormContainer', 50)
						->build();

		return $form;
	}
    
}
