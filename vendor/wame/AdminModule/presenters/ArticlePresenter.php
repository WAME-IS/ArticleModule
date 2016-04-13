<?php

namespace App\AdminModule\Presenters;

use Nette\Application\UI\Form;
use Wame\ArticleModule\Forms\ArticleForm;
use Wame\ArticleModule\Repositories\ArticleRepository;
use Wame\ArticleModule\Repositories\ArticleLangRepository;
use Wame\ArticleModule\Entities\ArticleEntity;

class ArticlePresenter extends \App\AdminModule\Presenters\BasePresenter
{	
	/** @var ArticleForm @inject */
	public $articleForm;

	/** @var ArticleRepository @inject */
	public $articleRepository;

	/** @var ArticleLangRepository @inject */
	public $articleLangRepository;

	/** @var ArticleEntity */
	private $articleEntity;

	public function startup() 
	{
		parent::startup();
		
		$this->articleEntity = $this->entityManager->getRepository(ArticleEntity::class);
	}
	
	protected function createComponentArticleForm()
	{
		$form = $this->articleForm->create();
		$form->setRenderer(new \Tomaj\Form\Renderer\BootstrapVerticalRenderer);
		
		if ($this->id) {
			$defaults = $this->articleEntity->findOneBy(['id' => $this->id]);

			$form['title']->setDefaultValue($defaults->title);
		}
		
		$form->onSuccess[] = [$this, 'articleFormSucceeded'];
		
		return $form;
	}
	
	public function articleFormSucceeded(Form $form, $values)
	{
		if ($this->id) {
			$this->articleRepository->set($this->id, $values);

			$this->flashMessage(_('The article was successfully update'), 'success');
		} else {
			$article = $this->articleRepository->add($values);
			$articleLang = $this->articleLangRepository->add($article, $values);
			
			$this->entityManager->persist($article);
			$this->entityManager->persist($articleLang);

			$this->flashMessage(_('The article was created successfully'), 'success');
		}
		
		$this->redirect('this');
	}
	
	public function renderDefault()
	{
		if ($this->id) {
			$this->template->siteTitle = _('Edit article');
		} else {
			$this->template->siteTitle = _('Add new article');
		}
	}
}
