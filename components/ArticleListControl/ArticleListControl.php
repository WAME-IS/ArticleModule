<?php

namespace Wame\ArticleModule\Components;

use Nette\ComponentModel\IContainer;
use Nette\DI\Container;
use Wame\ArticleModule\Components\ArticleListControl;
use Wame\ArticleModule\Entities\ArticleEntity;
use Wame\ChameleonComponents\IO\DataLoaderControl;
use Wame\ChameleonComponentsDoctrineListControl\Components\ChameleonListControl;

interface IArticleListControlFactory
{

    /** @return ArticleListControl */
    public function create();
}

class ArticleListControl extends ChameleonListControl implements DataLoaderControl
{

    public function __construct(Container $container, IArticleControlFactory $IArticleControlFactory, IArticleEmptyListControl $IArticleEmptyListControl, IContainer $parent = NULL, $name = NULL)
    {
        parent::__construct($container, $parent, $name);
        $this->setComponentFactory($IArticleControlFactory);
        $this->setNoItemsFactory($IArticleEmptyListControl);
    }

    public function getListType()
    {
        return ArticleEntity::class;
    }
}
