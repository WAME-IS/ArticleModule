<?php

namespace Wame\ArticleModule\Vendor\Wame\Core\Registers\Types;

use Wame\ArticleModule\Entities\ArticleEntity;
use Wame\Core\Registers\Types\StatusType;

class ArticleStatusType extends StatusType
{

    public function getTitle()
    {
        return _('Article');
    }

    public function getEntityName()
    {
        return ArticleEntity::class;
    }

}
