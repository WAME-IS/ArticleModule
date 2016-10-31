<?php

namespace App\AdminModule\Presenters;

use Wame\ArticleModule\Repositories\ArticleRepository;
use Wame\MenuModule\Forms\MenuItemForm;
use Wame\DynamicObject\Vendor\Wame\AdminModule\Presenters\AdminFormPresenter;

class ArticlePresenter extends AdminFormPresenter
{
	/** @var ArticleRepository @inject */
	public $repository;

	/** @var MenuItemForm @inject */
	public $menuItemForm;
    
	
	/** handlers **************************************************************/
	
    /**
     * Handle delete
     */
	public function handleDelete()
	{
		$this->repository->delete(['id' => $this->id]);
		
		$this->flashMessage(_('Article has been successfully deleted'), 'success');
		$this->redirect(':Admin:Article:', ['id' => null]);
	}
	
	
	/** renders ***************************************************************/
	
    /**
     * Render default
     */
	public function renderDefault()
	{
		$this->template->siteTitle = _('Articles');
        $this->template->count = $this->count;
	}
	
	/**
     * Render create
     */
	public function renderCreate()
	{
		$this->template->siteTitle = _('Create new article');
	}
	
	/**
     * Render edit
     */
	public function renderEdit()
	{
		$this->template->siteTitle = _('Edit article');
	}
	
	
	public function renderDelete()
	{
		$this->template->siteTitle = _('Deleting article');
	}
	
	
	public function renderMenuItem()
	{
		if ($this->id) {
			$this->template->siteTitle = _('Edit article item in menu');
		} else {
			$this->template->siteTitle = _('Add article item to menu');
		}
	}
	
	
	/** components ************************************************************/
	
	/**
	 * Menu item form component
	 * 
	 * @return MenuItemForm
	 */
	protected function createComponentArticleMenuItemForm()
	{
		$form = $this->menuItemForm
						->setActionForm('articleMenuItemForm')
						->setType('article')
						->setId($this->id)
						->addFormContainer(new \Wame\ArticleModule\Vendor\Wame\MenuModule\Components\MenuManager\Forms\ArticleFormContainer(), 'ArticleFormContainer', 50)
						->build();

		return $form;
	}
	
	/**
	 * ArticleList form component
	 * 
	 * @return ComponentForm
	 */
	protected function createComponentArticleListForm()
	{
		$form = $this->componentForm
						->setType('TextBlockComponent')
						->setId($this->id)
						->addFormContainer($this->textFormContainer, 'TextFormContainer', 75)
						->addFormContainer($this->showTitleFormContainer, 'ShowTitleFormContainer', 25)
						->build();

		return $form;
	}

    
    /** implements ************************************************************/
    
    /** {@inheritDoc} */
    protected function getFormBuilderServiceAlias()
    {
        return "Admin.ArticleFormBuilder";
    }
    
    /** {@inheritDoc} */
    protected function getGridServiceAlias()
    {
        return "Admin.ArticleGrid";
    }

}
