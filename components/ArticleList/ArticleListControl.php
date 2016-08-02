<?php

namespace Wame\ArticleModule\Components;

use Nette\ComponentModel\IContainer;
use Nette\DI\Container;
use Wame\ChameleonComponentsDoctrineListControl\Provider\ChameleonComponentsListProvider;
use Wame\ListControl\Components\ProvidedListControl;

interface IArticleListControlFactory
{

    /** @return ArticleListControl */
    public function create();
}

class ArticleListControl extends ProvidedListControl
{

    public function __construct(Container $container, IContainer $parent = NULL, $name = NULL)
    {
        parent::__construct($container, $parent, $name);
        $this->setProvider(new ChameleonComponentsListProvider());
    }
}
