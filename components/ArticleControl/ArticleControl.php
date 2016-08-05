<?php

namespace Wame\ArticleModule\Components;

use Doctrine\Common\Collections\Criteria;
use Nette\DI\Container;
use Wame\ArticleModule\Entities\ArticleEntity;
use Wame\ChameleonComponents\Definition\ControlDataDefinition;
use Wame\ChameleonComponents\Definition\DataDefinition;
use Wame\ChameleonComponents\Definition\DataDefinitionTarget;
use Wame\ChameleonComponents\IO\DataLoaderControl;
use Wame\Core\Components\BaseControl;
use Wame\ListControl\Components\IEntityControlFactory;

interface IArticleControlFactory extends IEntityControlFactory
{

    /** @return ArticleControl */
    public function create($entity);
}

class ArticleControl extends BaseControl implements DataLoaderControl
{

    public function __construct(Container $container, $entity)
    {
        parent::__construct($container);
        if ($entity) {
            $this->getStatus()->set('entity', $entity);
        }
    }

    /**
     * @param int $articleId
     */
    public function setArticleId($articleId)
    {
        $this->articleId = $articleId;
    }

    public function getDataDefinition()
    {
        $criteria = null;
        if ($this->articleId) {
            $criteria = Criteria::create()->where(Criteria::expr()->eq('id', $this->articleId));
        }

        return new ControlDataDefinition($this, new DataDefinition(new DataDefinitionTarget(ArticleEntity::class, false), $criteria));
    }

    public function render()
    {
        $this->template->article = $this->getStatus()->get('article');
    }
}