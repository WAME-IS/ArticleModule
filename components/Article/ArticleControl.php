<?php

namespace Wame\ArticleModule\Components;

use Wame\ArticleModule\Entities\ArticleEntity;
use Wame\ArticleModule\Repositories\ArticleRepository;

interface IArticleControlFactory
{
	/** @return ArticleControl */
	public function create();	
}


class ArticleControl extends \App\Core\Components\BaseControl
{	
	/** @var integer */
	protected $id;
	
	/** @var string */
	protected $slug;
	
	private $inList = false;
	
	/** @var ArticleEntity */
	protected $articleEntity;
	
	/** @var ArticleRepository */
	private $articleRepository;
	
	private $article;
	
	private $lang;
	
	
	public function __construct(ArticleRepository $articleRepository) {
		parent::__construct();
		
		$this->articleRepository = $articleRepository;
		$this->lang = $this->articleRepository->lang;
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
	

	public function render(ArticleEntity $articleEntity = null)
	{
		if($this->inList) {
			$this->setTemplateFile('default.latte');
		} else {
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
		
//		if($this->id) {
//			$articles = $this->getArticleById($this->id);
//		} else if($this->slug) {
//			$articles = $this->getArticleBySlug($this->slug);
//		} else {
//			$articles = $this->getArticles();
//		}
		
		$this->template->lang = $this->lang;
		$this->template->article = $this->article;
		
		$this->getTemplateFile();
		$this->template->render();
	}
	
	private function getArticleById($id)
	{
		return $this->articleRepository->get([
			'id' => $id,
//			'status' => ArticleRepository::STATUS_PUBLISHED
		]);
	}
	
	private function getArticleBySlug($slug)
	{
		return $this->articleRepository->get([
			'langs.slug' => $slug,
//			'status' => ArticleRepository::STATUS_PUBLISHED
		]);
	}
}