<?php

namespace Wame\ArticleModule\Components;

use Nette\ComponentModel\IContainer;
use Nette\DI\Container;
use Wame\ArticleModule\Repositories\ArticleRepository;
use Wame\ListControl\Components\EmptyListControl;

interface IArticleEmptyListControl
{

    /** @return ArticleEmptyListControl */
    public function create();
}

class ArticleEmptyListControl extends EmptyListControl
{

    /** @var ArticleRepository */
    private $articleRepository;

    public function __construct(Container $container, ArticleRepository $articleRepository, IContainer $parent = NULL, $name = NULL)
    {
        parent::__construct($container, $parent, $name);
        $this->articleRepository = $articleRepository;
    }

    protected function create()
    {
        $this->getPresenter()->redirect(":Article:article:");
    }
}
