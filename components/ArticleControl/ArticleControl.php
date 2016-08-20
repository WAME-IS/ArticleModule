<?php

namespace Wame\ArticleModule\Components;

use Doctrine\Common\Collections\Criteria;
use Nette\DI\Container;
use Wame\ArticleModule\Entities\ArticleEntity;
use Wame\ChameleonComponents\Definition\ControlDataDefinition;
use Wame\ChameleonComponents\Definition\DataDefinition;
use Wame\ChameleonComponents\Definition\DataDefinitionTarget;
use Wame\Core\Components\BaseControl;
use Wame\ListControl\Components\IEntityControlFactory;

interface IArticleControlFactory extends IEntityControlFactory
{

    /** @return ArticleControl */
    public function create($entity = null);
}

class ArticleControl extends BaseControl /* implements DataLoaderControl */
{

    /** @var int */
    private $articleId;
    
    public function __construct(Container $container, $entity = null)
    {
        parent::__construct($container);
        if ($entity) {
            $this->getStatus()->set(ArticleEntity::class, $entity);
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
