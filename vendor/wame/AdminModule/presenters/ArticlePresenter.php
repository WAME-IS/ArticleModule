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
		$form->setRenderer(new \Tomaj\Form\Renderer\BootstrapVerticalRenderer);
		
		if ($this->action == 'edit' && is_numeric($this->id)) {
			$defaults = $this->articleEntity->findOneBy(['id' => $this->id]);

			$form['title']->setDefaultValue($defaults->title);
		}
		
		$form->onSuccess[] = [$this, 'articleFormSucceeded'];
		
		return $form;
	}
	
	public function articleFormSucceeded(Form $form, $values)
	{
		
		if ($this->action == 'edit') {
			$this->articleRepository->set($this->id, $values);

			$this->flashMessage(_('The article was successfully update'), 'success');
		} elseif ($this->action == 'create') {
			try {
				$this->articleRepository->addArticle($values);

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
		$this->template->articleEntity = $this->articleRepository->getArticles(['status NOT IN (?)' => [ArticleRepository::STATUS_REMOVE]]);
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

}
