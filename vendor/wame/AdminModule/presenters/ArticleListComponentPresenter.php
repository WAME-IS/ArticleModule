<?php

namespace App\AdminModule\Presenters;

use Wame\ArticleCategoryPlugin\Vendor\Wame\AdminModule\Forms\ICategoryTreeFormContainerFactory;
use Wame\ArticleModule\Forms\IPaginatorVisibilityFormContainerFactory;
use Wame\ArticleModule\Forms\IFilterVisibilityFormContainerFactory;
use Wame\ArticleModule\Forms\ILimitFormContainerFactory;
use Wame\ArticleModule\Forms\ISortFormContainerFactory;
use Wame\Core\Presenters\Traits\UseParentTemplates;
use Wame\DynamicObject\Forms\IFormGroupContainerFactory;


class ArticleListComponentPresenter extends AbstractComponentPresenter
{
    use UseParentTemplates;


    /** @var IFormGroupContainerFactory @inject */
    public $IFormGroupContainerFactory;

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


    protected function getComponentIdentifier()
    {
        return 'ArticleListComponent';
    }


    protected function getComponentName()
    {
        return _('Article list');
    }


    protected function createComponentForm()
    {
        $this->attachFormContainer($this->IFormGroupContainerFactory->create(), 'EmptyGroup', 10);
        $this->attachFormContainer($this->IPaginatorVisibilityFormContainerFactory->create(), 'PaginatorVisibility', 10);
        $this->attachFormContainer($this->IFilterVisibilityFormContainerFactory->create(), 'FilterVisibility', 10);
        $this->attachFormContainer($this->ILimitFormContainerFactory->create(), 'LimitFormContainer', 10);
        $this->attachFormContainer($this->ISortFormContainerFactory->create(), 'SortFormContainer', 10);
        $this->attachFormContainer($this->ICategoryTreeFormContainer->create(), 'CategoryTreeFormContainer', 10);

        return parent::createComponentForm();
    }

}
