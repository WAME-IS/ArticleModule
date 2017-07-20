<?php

namespace Wame\ArticleModule\Components;

use Wame\ArticleModule\Entities\ArticleEntity;
use Wame\ChameleonComponents\Components\SingleEntityControl;
use Wame\ListControl\Components\IEntityControlFactory;


interface IArticleControlFactory extends IEntityControlFactory
{
    /**
     * @param ArticleEntity $entity
     * @return ArticleControl
     */
    public function create($entity = null);
}


class ArticleControl extends SingleEntityControl
{
    /** {@inheritdoc} */
    protected function getEntityType()
    {
        return ArticleEntity::class;
    }

}
