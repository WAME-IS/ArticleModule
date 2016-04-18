<?php

namespace App\AdminModule\Presenters;

use Nette\Application\UI\Form;
use Wame\ArticleModule\Forms\ArticleForm;
use Wame\ArticleModule\Repositories\ArticleRepository;

class ArticlePresenter extends \App\AdminModule\Presenters\BasePresenter
{	
	/** @var ArticleForm @inject */
	public $articleForm;

	/** @var ArticleRepository @inject */
	public $articleRepository;

	protected function createComponentArticleForm()
	{
		$form = $this->articleForm->create();
		
		if ($this->action == 'edit' && is_numeric($this->id)) {
			$defaults = $this->articleRepository->get(['id' => $this->id]);
			$defaultsLang = $defaults->langs[$this->lang];

			$form->setDefaults([
				'title' => $defaultsLang->title,
				'slug' => $defaultsLang->slug,
				'status' => $defaults->status,
				'description' => $defaultsLang->description,
				'text' => $defaultsLang->text
			]);
			
			if ($defaults->publishStartDate) {
				$form['publish_start_date']->setDefaultValue($this->formatDate($defaults->publishStartDate));
			}
			if ($defaults->publishEndDate) {
				$form['publish_end_date']->setDefaultValue($this->formatDate($defaults->publishEndDate));
			}
		}
		
		$form->onSuccess[] = [$this, 'articleFormSucceeded'];
		
		return $form;
	}
	
	public function articleFormSucceeded(Form $form, $values)
	{
		if ($this->action == 'edit') {
			try {
				$this->articleRepository->set($this->id, $values);

				$this->flashMessage(_('The article was successfully update'), 'success');
			} catch (\Exception $e) {
				$form->addError($e->getMessage());
			}
		} elseif ($this->action == 'create') {
			try {
				$this->articleRepository->add($values);

				$this->flashMessage(_('The article was created successfully'), 'success');
			} catch (\Exception $e) {
				$form->addError($e->getMessage());
			}
		}
		
		$this->redirect('this');
	}

	
	public function renderDefault()
	{
		$this->template->siteTitle = _('Articles');
		$this->template->articles = $this->articleRepository->getAll(['status NOT IN (?)' => [ArticleRepository::STATUS_REMOVE]]);
	}
	
	
	public function renderCreate()
	{
		$this->template->siteTitle = _('Create new article');
		$this->template->setFile(__DIR__ . '/templates/Article/edit.latte');
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
