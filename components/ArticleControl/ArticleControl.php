<?php

namespace Wame\ArticleModule\Components;

use Wame\ArticleModule\Entities\ArticleEntity;
use Wame\Core\Components\SingleEntityControl;
use Wame\ListControl\Components\IEntityControlFactory;

interface IArticleControlFactory extends IEntityControlFactory
{

    /** @return ArticleControl */
    public function create($entity = null);
}

class ArticleControl extends SingleEntityControl
{

    protected function getEntityType()
    {
        return ArticleEntity::class;
    }
}
