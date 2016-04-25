<?php

namespace Wame\ArticleModule\Controls;

class ArticleControl extends BaseControl
{	
	/** @var integer */
	private $count = 5;
	
	/** @var integer */
	private $itemsPerPage = 2;
	
	/** @var integer */
	private $paginatorOffset;
	
	/** @var integer */
	protected $id;
	
	/** @var string */
	protected $slug;
	
	
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
	 * Set items per page
	 * 
	 * @param type $itemsPerPage
	 */
	public function setItemsPerPage($itemsPerPage)
	{
		$this->itemsPerPage = $itemsPerPage;
	}
	
	/**
	 * Set count of items in paginator
	 * @param type $count
	 */
	public function setCount($count)
	{
		$this->count = $count;
	}
	
	protected function createComponentPaginator()
	{
		return new \NettePagination;
	}

	public function render()
	{
		$this->setTemplate('article');
		
		if($this->id) {
			$articles = $this->getArticleById($this->id);
		} else if($this->slug) {
			$articles = $this->getArticleBySlug($this->slug);
		} else {
			$articles = $this->getArticles();
		}
		
		$this->template->articles = $articles;
		$this->template->paginatorOffset = $this->paginatorOffset;
		$this->template->render();
	}
	
	private function getArticleById($id)
	{
		return $this->articleRepository->getAll([
			'id' => $id,
//				'status' => ArticleRepository::STATUS_PUBLISHED
		]);
	}
	
	private function getArticleBySlug($slug)
	{
		return $this->articleRepository->getAll([
			'langs.slug' => $slug,
//				'status' => ArticleRepository::STATUS_PUBLISHED
		]);
	}
	
	private function getArticles()
	{
		$vp = $this['paginator'];
		$vp->setCount($this->count);
		$paginator = $vp->getPaginator();
		$paginator->itemsPerPage = $this->itemsPerPage;
		$this->paginatorOffset = $paginator->offset;
		$paginator->itemCount = $this->articleRepository->countBy();

		return $this->articleRepository->getAll([
//				'status' => ArticleRepository::STATUS_PUBLISHED
		], null, $paginator->itemsPerPage, $paginator->offset);
	}
}