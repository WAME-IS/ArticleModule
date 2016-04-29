<?php

namespace App\AdminModule\Presenters;

use Wame\ArticleModule\Vendor\Wame\AdminModule\Forms\CreateArticleForm;
use Wame\ArticleModule\Vendor\Wame\AdminModule\Forms\EditArticleForm;
use Wame\ArticleModule\Repositories\ArticleRepository;

class ArticlePresenter extends \App\AdminModule\Presenters\BasePresenter
{	
	/** @var CreateArticleForm @inject */
	public $createArticleForm;
	
	/** @var EditArticleForm @inject */
	public $editArticleForm;

	/** @var ArticleRepository @inject */
	public $articleRepository;

	
	/**
	 * Create article
	 * 
	 * @return EditUserForm		form
	 */
	protected function createComponentCreateArticleForm() 
	{
		$form = $this->createArticleForm->build();
		
		return $form;
	}
	
	/**
	 * Edit article
	 * 
	 * @return EditUserForm		form
	 */
	protected function createComponentEditArticleForm() 
	{
		$form = $this->editArticleForm->setId($this->id)->build();

		return $form;
	}
	
	public function renderDefault()
	{
		$this->template->siteTitle = _('Articles');
		$this->template->articles = $this->articleRepository->find(['status NOT IN (?)' => [ArticleRepository::STATUS_REMOVE]]);
	}
	
	
	public function renderCreate()
	{
		$this->template->siteTitle = _('Create new article');
	}
	
	
	public function renderEdit()
	{
		$this->template->siteTitle = _('Edit article');
	}
	
	
	public function renderDelete()
	{
		$this->template->siteTitle = _('Deleting article');
	}
	
	
	public function handleDelete()
	{
		$this->articleRepository->delete(['id' => $this->id]);
		
		$this->flashMessage(_('Article has been successfully deleted'), 'success');
		$this->redirect(':Admin:Article:', ['id' => null]);
	}

}
