<?php

namespace App\AdminModule\Presenters;

use Kdyby\Doctrine\EntityManager;
use Wame\ArticleModule\Vendor\Wame\AdminModule\Forms\CreateArticleForm;
use Wame\ArticleModule\Vendor\Wame\AdminModule\Forms\EditArticleForm;
use Wame\ArticleModule\Repositories\ArticleRepository;
use Wame\ArticleModule\Vendor\Wame\AdminModule\Grids\ArticleGrid;
use Wame\MenuModule\Forms\MenuItemForm;

class ArticlePresenter extends \App\AdminModule\Presenters\BasePresenter
{	
	/** @var CreateArticleForm @inject */
	public $createArticleForm;
	
	/** @var EditArticleForm @inject */
	public $editArticleForm;

	/** @var ArticleRepository @inject */
	public $articleRepository;
	
	/** @var EntityManager @inject */
	public $entityManager;
	
	/** @var ArticleGrid @inject */
	public $articleGrid;

	/** @var MenuItemForm @inject */
	public $menuItemForm;
	
	
	/** handlers **************************************************************/
	
	public function handleDelete()
	{
		$this->articleRepository->delete(['id' => $this->id]);
		
		$this->flashMessage(_('Article has been successfully deleted'), 'success');
		$this->redirect(':Admin:Article:', ['id' => null]);
	}
	
	
	/** renders ***************************************************************/
	
	public function renderDefault()
	{
		$this->template->siteTitle = _('Articles');
		$this->template->articles = $this->articleRepository->find(['status NOT IN (?)' => [ArticleRepository::STATUS_REMOVE]]);
	}
	
	
	public function renderCreate()
	{
		$this->template->siteTitle = _('Create new article');
	}
	
	
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
	 * Create article form component
	 * 
	 * @return CreateArticleForm	form
	 */
	protected function createComponentCreateArticleForm() 
	{
		$form = $this->createArticleForm->build();
		
		return $form;
	}
	
	/**
	 * Edit article form component
	 * 
	 * @return EditUserForm		form
	 */
	protected function createComponentEditArticleForm() 
	{
		$form = $this->editArticleForm->setId($this->id)->build();

		return $form;
	}
	
    /**
     * Article grid component
     * 
     * @return type
     */
	protected function createComponentArticleGrid()
	{
        $qb = $this->articleRepository->createQueryBuilder('a');
		$this->articleGrid->setDataSource($qb);
		
		return $this->articleGrid;
	}
	
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
	
}
