<?php

namespace Wame\ArticleModule\Components;

use Nette\DI\Container;
use Nette\DI\Container;
use Wame\ArticleModule\Entities\ArticleEntity;
use Wame\Core\Components\BaseControl;
use Wame\ListControl\Components\IEntityControlFactory;

interface IArticleControlFactory extends IEntityControlFactory
{

    /** @return ArticleControl */
    public function create($entity);
}

class ArticleControl extends BaseControl
{

    private $article;

    public function __construct(Container $container, ArticleEntity $entity)
    {
        parent::__construct($container);
        $this->article = $entity;
    }

    public function render()
    {
        $this->template->article = $this->article;
    }
}
