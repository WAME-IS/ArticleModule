<?php

namespace Wame\ArticleModule\Components;

use Wame\ArticleModule\Entities\ArticleEntity;
use Wame\ArticleModule\Repositories\ArticleRepository;

use Wame\PositionModule\Components\IPositionControlFactory;

interface IArticleControlFactory
{
	/** @return ArticleControl */
	public function create();	
}


class ArticleControl extends \Wame\Core\Components\BaseControl
{
	/** @var integer */
	protected $id;
	
	/** @var string */
	protected $slug;
	
	/** @var boolean */
	private $inList = false;
	
	/** @var ArticleEntity */
	protected $articleEntity;
	
	/** @var ArticleRepository */
	private $articleRepository;
	
	/** @var ArticleEntity */
	private $article;
	
	/** @var string */
	private $lang;
    
    private $IPositionControlFactory;
	
	
	public function __construct(ArticleRepository $articleRepository, IPositionControlFactory $IPositionControlFactory)
    {
		parent::__construct();
		
		$this->articleRepository = $articleRepository;
		$this->lang = $this->articleRepository->lang;
        
//        $this->getPresenter()->getStatus()->set('meta', 'test');
        
        $this->IPositionControlFactory = $IPositionControlFactory;
	}
	
	
	/**
	 * Set id
	 * 
	 * @param type $id
	 */
	public function setId($id)
	{
		$this->id = $id;
	}
	
	/**
	 * Set slug
	 * 
	 * @param type $slug
	 */
	public function setSlug($slug)
	{
		$this->slug = $slug;
	}
	
	/**
	 * Set if in list
	 * 
	 * @param boolean $inList
	 */
	public function setInList($inList)
	{
		$this->inList = $inList;
	}
	
	/**
	 * Render
	 * 
	 * @param ArticleEntity $articleEntity	article
	 */
	public function render($parameters = [])
	{
		$articleEntity = isset($parameters['entity']) ? $parameters['entity'] : null;
		$template = isset($parameters['template']) ? $parameters['template'] : null;
		
		if($template) {
			$this->setTemplateFile($template);
		}
		else if($this->inList) {
			$this->setTemplateFile('default.latte');
		}
		else {
			$this->setTemplateFile('detail.latte');
		}
		
		if($articleEntity) {
			$this->article = $articleEntity;
		} else {
			if($this->id) {
				$this->article = $this->getArticleById($this->id);
			} else if($this->slug) {
				$this->article = $this->getArticleBySlug($this->slug);
			}
		}
        
        $this->getStatus()->set('article', $this->article);
		
		$this->template->lang = $this->lang;
		$this->template->article = $this->article;
		
		$this->getTemplateFile();
		$this->template->render();
	}
	
	
	/**
	 * Get article by ID
	 * 
	 * @param integer $id		ID
	 * @return ArticleEntity	article
	 */
	private function getArticleById($id)
	{
		return $this->articleRepository->get([
			'id' => $id,
			'status' => ArticleRepository::STATUS_PUBLISHED
		]);
	}
	
	/**
	 * Get article by slug
	 * 
	 * @param string $slug		slug
	 * @return ArticleEntity	article
	 */
	private function getArticleBySlug($slug)
	{
		return $this->articleRepository->get([
			'langs.slug' => $slug,
			'status' => ArticleRepository::STATUS_PUBLISHED
		]);
	}
    
    
    /**
     * Position control
     * 
     * @return IPositionControlFactory
     */
    protected function createComponentPositionControl()
    {
        $control = $this->IPositionControlFactory->create();

        return $control;
    }
    
}