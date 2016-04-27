<?php

namespace Wame\ArticleModule\Controls;

use Wame\ArticleModule\Controls\Article;

class ArticleList extends BaseControl
{	
	/** @var integer */
	private $count = 5;
	
	/** @var integer */
	private $itemsPerPage = 2;
	
	/** @var integer */
	private $paginatorOffset;
	
	
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
		return new \Wame\Utils\Pagination;
	}
	
//	protected function createComponentArticle()
//	{
//		return new Article;
//	}

	public function render()
	{
		$this->setTemplate('article_list');
		
		$articles = $this->getArticles();
		
		$this->template->articles = $articles;
		$this->template->paginatorOffset = $this->paginatorOffset;
		$this->template->render();
	}
	
	private function getArticles()
	{
		$vp = $this['paginator'];
		$vp->setCount($this->count);
		$paginator = $vp->getPaginator();
		$paginator->itemsPerPage = $this->itemsPerPage;
		$this->paginatorOffset = $paginator->offset;
		$paginator->itemCount = $this->articleRepository->countBy();

		return $this->articleRepository->find([
//				'status' => ArticleRepository::STATUS_PUBLISHED
		], null, $paginator->itemsPerPage, $paginator->offset);
	}
}