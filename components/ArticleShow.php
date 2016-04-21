<?php

namespace Wame\ArticleModule\Controls;

use Wame\ArticleModule\Repositories\ArticleRepository;

class ArticleShow extends BaseControl
{	
	/** @var integer */
	protected $id;
	
	public function setId($id)
	{
		$this->id = $id;
	}
	
	public function render($id = null)
	{
		$this->setTemplate('show');
		
		$article = $this->articleRepository->get([
			'id' => $this->id?:$id,
//			'status' => ArticleRepository::STATUS_PUBLISHED
		]);
		
		if($article) {
			$this->template->article = $article;
			$this->template->render();
		} else {
//			$this->redirect(':Article:Article:default', ['id' => null]);
		}
	}
}