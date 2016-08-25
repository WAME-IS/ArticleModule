<?php

namespace Wame\ArticleModule\Vendor\Wame\Core\Registers\Types;

class ArticleStatusType extends \Wame\Core\Registers\Types\StatusType
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