<?php

namespace Wame\ArticleModule\Components;

use Nette\ComponentModel\IContainer;
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

    public function __construct(Container $container, IContainer $parent = NULL, $name = NULL)
    {
        parent::__construct($container, $parent, $name);
        $this->setProvider(new ChameleonComponentsListProvider());
        $this->setComponentFactory($componentFactory);
        $this->setNoItemsFactory($noItemsFactory)
    }

    public function getDataDefinition()
    {
        return new ControlDataDefinition($this, new DataDefinition(new DataDefinitionTarget(ArticleEntity::class, true)));
    }
}
