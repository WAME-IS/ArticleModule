<?php

namespace Wame\ArticleModule\Controls;

use Wame\ArticleModule\Entities\ArticleEntity;

class Article extends BaseControl
{	
	/** @var integer */
	protected $id;
	
	/** @var string */
	protected $slug;
	
	/** @var ArticleEntity */
	protected $articleEntity;
	
	private $article;
	
	
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
	

	public function render(ArticleEntity $articleEntity = null)
	{
		$this->setTemplate('article');
		
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
		
//		dump($this->article); exit;
		
		$this->template->article = $this->article;
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