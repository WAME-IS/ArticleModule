<?php

namespace Wame\ArticleModule\Components;

use Nette\ComponentModel\IContainer;
use Nette\DI\Container;
use Wame\ArticleModule\Components\ArticleListControl;
use Wame\ArticleModule\Entities\ArticleEntity;
use Wame\ChameleonComponents\Definition\ControlDataDefinition;
use Wame\ChameleonComponents\Definition\DataDefinition;
use Wame\ChameleonComponents\Definition\DataDefinitionTarget;
use Wame\ChameleonComponents\IO\DataLoaderControl;
use Wame\ChameleonComponentsDoctrineListControl\Provider\ChameleonComponentsListProvider;
use Wame\ListControl\Components\ProvidedListControl;

interface IArticleListControlFactory
{

    /** @return ArticleListControl */
    public function create();
}

class ArticleListControl extends ProvidedListControl implements DataLoaderControl
{

    public function __construct(Container $container, IArticleControlFactory $IArticleControlFactory, IArticleEmptyListControl $IArticleEmptyListControl, IContainer $parent = NULL, $name = NULL)
    {
        parent::__construct($container, $parent, $name);
        $this->setProvider(new ChameleonComponentsListProvider());
        $this->setComponentFactory($IArticleControlFactory);
        $this->setNoItemsFactory($IArticleEmptyListControl);
    }

    public function getDataDefinition()
    {
        return new ControlDataDefinition($this, new DataDefinition(new DataDefinitionTarget(ArticleEntity::class, true)));
    }
}
