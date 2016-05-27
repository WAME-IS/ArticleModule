<?php

namespace App\AdminModule\Presenters;

use Wame\ArticleModule\Vendor\Wame\AdminModule\Forms\CreateArticleForm;
use Wame\ArticleModule\Vendor\Wame\AdminModule\Forms\EditArticleForm;
use Wame\ArticleModule\Repositories\ArticleRepository;
use Wame\MenuModule\Forms\MenuItemForm;

use Wame\DataGridControl\IDataGridControlFactory;
use Wame\ArticleModule\Vendor\Wame\AdminModule\Grids\ArticleGrid;

use Wame\GalleryModule\Controls\GalleryPickerControl;
use Wame\GalleryModule\Controls\GalleryPicker2Control;


use Kdyby\Doctrine\EntityManager;

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
	
	/** @var IDataGridControlFactory @inject */
	public $gridControl;
	
	/** @var ArticleGrid @inject */
	public $articleGrid;

	/** @var MenuItemForm @inject */
	public $menuItemForm;
	
	/** @var GalleryPickerControl @inject */
	public $galleryPickerControl;
	
	/** @var GalleryPicker2Control @inject */
	public $galleryPicker2Control;
	
	
	
	/** components ************************************************************/
	
	/**
	 * Create article
	 * 
	 * @return CreateArticleForm	form
	 */
	protected function createComponentCreateArticleForm() 
	{
		$form = $this->createArticleForm->build();
		
		return $form;
	}
	
	
	/**
	 * Edit article
	 * 
	 * @return EditUserForm		form
	 */
	protected function createComponentEditArticleForm() 
	{
		$form = $this->editArticleForm->setId($this->id)->build();

		return $form;
	}
	
	public function createComponentArticleGrid($name)
	{
		$grid = $this->gridControl->create();
		$grid->setGridName('article');
		$articles = $this->articleRepository->find(['status NOT IN (?)' => [ArticleRepository::STATUS_REMOVE]]);
		$grid->setDataSource($articles);
//		$grid->setLang($this->lang); // TODO: presunut logiku do komponenty
		
		$grid->setProvider($this->articleGrid);
		

		
		return $grid;
	}
	
	// TODO: presunut do dynamic forms!!!
	public function createComponentGalleryPicker()
	{
		$control = $this->galleryPickerControl;
		$control->setItem(0);
		return $control;
	}
	
	public function createComponentGalleryPicker2()
	{
		$control = $this->galleryPicker2Control;
		return $control;
	}
	
	
	/**
	 * Menu item form
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
	 * Menu component form
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
	
	
	public function handleDelete()
	{
		$this->articleRepository->delete(['id' => $this->id]);
		
		$this->flashMessage(_('Article has been successfully deleted'), 'success');
		$this->redirect(':Admin:Article:', ['id' => null]);
	}
}
