<?php

namespace App\ArticleModule\Presenters;

use Wame\ArticleModule\Components\IArticleControlFactory;
use Wame\ArticleModule\Components\IArticleListControlFactory;

//use Wame\FilterModule\Controls\SortControl;
use Wame\ArticleModule\Repositories\ArticleRepository;

use Wame\CategoryModule\Components\ICategoryListControlFactory;

use Wame\TagModule\Controls\TagListControl;

use Wame\HeadControl\MetaTitle;
use Wame\HeadControl\MetaDescription;

class ArticlePresenter extends \App\Core\Presenters\BasePresenter
{
	/** @var ArticleRepository @inject */
	public $articleRepository;
	
	/** @var IArticleControlFactory @inject */
	public $IArticleControlFactory;
	
	/** @var IArticleListControlFactory @inject */
	public $IArticleListControlFactory;
	
//	/** @var SortControl @inject */
//	public $sortControl;
	
	/** @var ICategoryListControlFactory @inject */
	public $ICategoryListControlFactory;
	
	/** @var integer */
	protected $articleId;
	
	/** @var string */
	protected $articleSlug;
	
//	private $filterBuilder;
	
	
	/** @persistent */
    public $page;
	
	/** @persistent */
    public $orderBy;
	
	/** @persistent */
    public $sort;
	
	/** @persistent */
    public $year;
	
	/** @persistent */
    public $month;
	
	/** @persistent */
    public $author;
	
    
    /** actions ***************************************************************/
    
    public function actionShow($id) {
		// TODO: poriesit vyber cez slugy
		$this->articleId = $id;
        
        $article = $this->articleRepository->getArticle(['id' => $this->articleId]);
        
        $this->getStatus()->set('article', $article);
        
        // set meta
        $this->status->set('meta', ['alias' => 'article', 'id' => $article->id]);
		
		$title = $article->langs[$this->lang]->title;
		$description = $article->langs[$this->lang]->description;
		
		$component = $this->headControl;
		$component->getMetaType(new MetaTitle)->setContent($title);
		$component->getMetaType(new MetaDescription)->setContent($description);
		
		$this->articleRepository->onRead($id);
	}
    
    
    /** renders ***************************************************************/
    
	public function renderDefault()
	{
		$this->template->siteTitle = _('Articles');
	}
	
	
    /** components ************************************************************/
	
	protected function createComponentArticle()
	{
		$articleId = $this->getParameter('id');
		
		$component = $this->IArticleControlFactory->create();
		$component->setId($articleId);
        
		$component->addComponent($this->createComponentCategoryList(), 'categoryList');
		
		return $component;
	}
	
	protected function createComponentArticleList()
	{
		$sort = $this->sort;
		
		$component = $this->IArticleListControlFactory->create();

		$articleComponent = $this->createComponentArticle();
		$articleComponent->setInList(true);
		
//		$component->addComponent($this->createComponentSortControl(), 'sort');
//		$component->setSortBy($sort);
		return $component;
	}
	
	protected function createComponentSortControl()
	{
		$sortControl = $this->sortControl;
		return $sortControl;
	}
	
	protected function createComponentCategoryList()
	{
		$control = $this->ICategoryListControlFactory->create();
		return $control;
	}
    
}
