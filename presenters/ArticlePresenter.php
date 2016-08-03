<?php

namespace App\ArticleModule\Presenters;

use App\Core\Presenters\BasePresenter;
use Wame\ArticleModule\Components\IArticleControlFactory;
use Wame\ArticleModule\Components\IArticleListControlFactory;

class ArticlePresenter extends BasePresenter
{

    /** @var IArticleListControlFactory */
    private $IArticleListControlFactory;

    /** @var IArticleControlFactory */
    private $IArticleControlFactory;

    public function injectArticleList(IArticleListControlFactory $IArticleListControlFactory)
    {
        $this->IArticleListControlFactory = $IArticleListControlFactory;
    }

    public function actionDefault()
    {
        $articleListControl = $this->IArticleListControlFactory->create();
        $this->addComponent($articleListControl, 'articleList');
    }

    public function actionShow($id)
    {
        $articleControl = $this->IArticleControlFactory->create();
        $articleControl->setArticleId($id);
        $this->addComponent($articleControl, 'article');
    }
    
    public function renderShow()
    {
        $this->bindMeta($this['article']);
    }
    
}
