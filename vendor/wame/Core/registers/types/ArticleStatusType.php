<?php

namespace Wame\ArticleModule\Vendor\Wame\Core\Registers\Types;

class ArticleStatusType implements \Wame\Core\Registers\Types\IStatusType
{
    public function getStatusName()
    {
        return "article";
    }

    public function getTitle()
    {
        return _('Article');
    }
    
    public function getEntityName()
    {
        return \Wame\ArticleModule\Entities\ArticleEntity::class;
    }
    
}
