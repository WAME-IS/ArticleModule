<?php

namespace Wame\ArticleModule\Vendor\Wame\ComponentModule;

use Nette\Application\LinkGenerator;
use Wame\ComponentModule\Models\IComponent;
use Wame\MenuModule\Models\Item;
use Wame\ArticleModule\Components\IArticleListControlFactory;

interface IArticleListComponentFactory
{
    /** @return ArticleListComponent */
    public function create();   
}


class ArticleListComponent implements IComponent
{   
    /** @var LinkGenerator */
    private $linkGenerator;

    /** @var IArticleListControlFactory */
    private $IArticleListControlFactory;


    public function __construct(
        LinkGenerator $linkGenerator,
        IArticleListControlFactory $IArticleListControlFactory
    ) {
        $this->linkGenerator = $linkGenerator;
        $this->IArticleListControlFactory = $IArticleListControlFactory;
    }


    public function addItem()
    {
        $item = new Item();
        $item->setName($this->getName());
        $item->setTitle($this->getTitle());
        $item->setDescription($this->getDescription());
        $item->setLink($this->getLinkCreate());
        $item->setIcon($this->getIcon());

        return $item->getItem();
    }


    public function getName()
    {
        return 'articleList';
    }


    public function getTitle()
    {
        return _('Article list');
    }


    public function getDescription()
    {
        return _('Create article list');
    }


    public function getIcon()
    {
        return 'fa fa-list-alt';
    }


    public function getLinkCreate()
    {
        return $this->linkGenerator->link('Admin:ArticleList:create');
    }


    public function getLinkDetail($componentEntity)
    {
        return $this->linkGenerator->link('Admin:ArticleList:edit', ['id' => $componentEntity->id]);
    }


    public function createComponent($componentInPosition)
    {
        $control = $this->IArticleListControlFactory->create();
        $control->setComponentInPosition($componentInPosition);

        return $control;
    }

}