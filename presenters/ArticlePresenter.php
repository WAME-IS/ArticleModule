<?php

namespace App\ArticleModule\Presenters;

use App\Core\Presenters\BasePresenter;
use Wame\ArticleModule\Components\IArticleControlFactory;
use Wame\ArticleModule\Components\IArticleListControlFactory;
use Wame\ArticleModule\Components\ArticleControl;

class ArticlePresenter extends BasePresenter
{
    /** @var IArticleListControlFactory */
    private $IArticleListControlFactory;

    /** @var IArticleControlFactory */
    private $IArticleControlFactory;
    
    /** @var ArticleControl */
    private $articleControl;

    
    public function injectArticleList(IArticleListControlFactory $IArticleListControlFactory, IArticleControlFactory $IArticleControlFactory)
    {
        $this->IArticleListControlFactory = $IArticleListControlFactory;
        $this->IArticleControlFactory = $IArticleControlFactory;
    }

    
    /** Execution *************************************************************/
    
    public function actionDefault()
    {
        $articleListControl = $this->IArticleListControlFactory->create();
        $this->addComponent($articleListControl, 'articleList');
    }

    public function actionShow()
    {
        $this->articleControl = $this->IArticleControlFactory->create();
        $this->articleControl->setEntityId($this->id);
        $this->addComponent($this->articleControl, 'article');
    }
    
    
    /** Interaction ***********************************************************/
    
    
    /** Rendering *************************************************************/
    
    public function renderDefault()
    {
        $this->template->siteTitle = _('Articles');
    }
    
    
    public function renderShow()
    {
        $this->template->siteTitle = $this->articleControl->getEntity()->getTitle();
    }
    
}
