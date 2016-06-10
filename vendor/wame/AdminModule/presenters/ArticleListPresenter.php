<?php

namespace App\AdminModule\Presenters;

use Wame\ComponentModule\Forms\ComponentForm;
use Wame\PositionModule\Repositories\PositionRepository;
use Wame\ArticleCategoryPlugin\Wame\ArticleModule\Wame\AdminModule\Forms\ICategoryTreeFormContainerFactory;
use Wame\ArticleModule\Forms\IPaginatorVisibilityFormContainerFactory;
use Wame\ArticleModule\Forms\IFilterVisibilityFormContainerFactory;
use Wame\ArticleModule\Forms\ILimitFormContainerFactory;
use Wame\ArticleModule\Forms\ISortFormContainerFactory;
use Wame\ArticleModule\Forms\ArticleListForm;

class ArticleListPresenter extends ComponentPresenter
{		
	/** @var ComponentForm @inject */
	public $componentForm;

	/** @var PositionRepository @inject */
	public $positionRepository;
	
	/** @var ICategoryTreeFormContainerFactory @inject */
	public $ICategoryTreeFormContainer;
	
	/** @var ILimitFormContainerFactory @inject */
	public $ILimitFormContainerFactory;
	
	/** @var ISortFormContainerFactory @inject */
	public $ISortFormContainerFactory;
	
	/** @var IPaginatorVisibilityFormContainerFactory @inject */
	public $IPaginatorVisibilityFormContainerFactory;
	
	/** @var IFilterVisibilityFormContainerFactory @inject */
	public $IFilterVisibilityFormContainerFactory;
	
	/** @var ArticleListForm @inject */
	public $articleListForm;
	
	
	
	
	public function actionCreate()
	{
		if (!$this->user->isAllowed('articleList', 'create')) {
			$this->flashMessage(_('To enter this section you do not have enough privileges.'), 'danger');
			$this->redirect(':Admin:Dashboard:');
		}
		
		if ($this->getParameter('p')) {
			$position = $this->positionRepository->get(['id' => $this->getParameter('p')]);
			
			if (!$position) {
				$this->flashMessage(_('This position does not exist.'), 'danger');
				$this->redirect(':Admin:Component:', ['id' => null]);
			}
			
			if ($position->status == PositionRepository::STATUS_REMOVE) {
				$this->flashMessage(_('This position is removed.'), 'danger');
				$this->redirect(':Admin:Component:', ['id' => null]);
			}
			
			if ($position->status == PositionRepository::STATUS_DISABLED) {
				$this->flashMessage(_('This position is disabled.'), 'warning');
			}
		}
	}
	
	
	public function actionEdit()
	{
		if (!$this->user->isAllowed('articleList', 'edit')) {
			$this->flashMessage(_('To enter this section you do not have enough privileges.'), 'danger');
			$this->redirect(':Admin:Dashboard:');
		}
	}
	

	/**
	 * Menu component form
	 * 
	 * @return ComponentForm
	 */
	protected function createComponentArticleListForm()
	{
		$form = $this->componentForm
						->setType('ArticleListComponent')
						->setId($this->id)
						->addFormContainer($this->IPaginatorVisibilityFormContainerFactory->create(), 'PaginatorVisibility')
						->addFormContainer($this->IFilterVisibilityFormContainerFactory->create(), 'FilterVisibility')
						->addFormContainer($this->ILimitFormContainerFactory->create(), 'LimitFormContainer')
						->addFormContainer($this->ISortFormContainerFactory->create(), 'SortFormContainer')
						->addFormContainer($this->ICategoryTreeFormContainer->create(), 'CategoryTreeFormContainer')
						->build();
		
		return $form;
	}
	
	
	public function renderCreate()
	{
		$this->template->siteTitle = _('Create article list');
	}
	
	
	public function renderEdit()
	{
		$this->template->siteTitle = _('Edit article list');
	}
	
}
