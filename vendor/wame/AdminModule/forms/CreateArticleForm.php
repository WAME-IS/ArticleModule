<?php

namespace Wame\ArticleModule\Vendor\Wame\AdminModule\Forms;

use Nette\Security\User;
use Nette\Application\UI\Form;
use Wame\Core\Forms\FormFactory;
use Wame\ArticleModule\Entities\ArticleEntity;
use Wame\ArticleModule\Entities\ArticleLangEntity;
use Wame\UserModule\Repositories\UserRepository;
use Wame\ArticleModule\Repositories\ArticleRepository;

class CreateArticleForm extends FormFactory
{	
	/** @val ArticleRepository */
	private $articleRepository;
	
	/** @val UserEntity */
	private $userEntity;
	
	/** @val string */
	private $lang;
	
	
	public function __construct(ArticleRepository $articleRepository, UserRepository $userRepository, User $user) {
		$this->articleRepository = $articleRepository;
		$this->userEntity = $userRepository->get(['id' => $user->id]);
		$this->lang = $articleRepository->lang;
	}
	
	
	// TODO: premenovat na build / generate?
	public function create()
	{
		$form = $this->createForm();
		
		$form->addSubmit('submit', _('Create article'));

		$form->onSuccess[] = [$this, 'formSucceeded'];
		
		return $form;
	}
	
	
	public function formSucceeded(Form $form, $values)
	{
		$presenter = $form->getPresenter();

		try {
			if ($presenter->action == 'edit') {
				$articleEntity = $this->updateArticle($presenter->id, $values);
				
				$this->articleRepository->onUpdate($form, $values, $articleEntity);
				
				$presenter->flashMessage(_('The article was successfully updated.'), 'success');
			} elseif ($presenter->action == 'create') {
				$articleEntity = $this->createArticle($values);
				
				$this->articleRepository->onCreate($form, $values, $articleEntity);

				$presenter->flashMessage(_('The article was successfully created.'), 'success');
			}
			
			$presenter->redirect('this');
		} catch (\Exception $e) {
			if ($e instanceof \Nette\Application\AbortException) {
				throw $e;
			}
			
			$form->addError($e->getMessage());
		}
	}
	
//	public function formSucceeded(Form $form, $values)
//	{
//		$presenter = $form->getPresenter();
//		
//		if ($presenter->action == 'edit') { // TODO: premenovat asi na update?!
//			try {
//				$this->articleRepository->update($this->id, $values);
//
//				$presenter->flashMessage(_('The article was successfully update'), 'success');
//			} catch (\Exception $e) {
//				$form->addError($e->getMessage());
//			}
//		} elseif ($presenter->action == 'create') {
//			try {
//				$articleEntity = $this->articleRepository->create($values);
//				
//				$this->articleRepository->onCreate($form, $values, $articleEntity);
//
//				$presenter->flashMessage(_('The article was created successfully'), 'success');
//			} catch (\Exception $e) {
//				$form->addError($e->getMessage());
//			}
//		}
//		
//		$this->redirect('this');
//	}
	
	public function createArticle($values)
	{
		$articleEntity = new ArticleEntity();
		if ($values['publish_start_date']) {
			$articleEntity->publisStartDate = $this->formatDate($values['publish_start_date']);
		}
		if ($values['publish_end_date']) {
			$articleEntity->publisEndDate = $this->formatDate($values['publish_end_date']);
		}
		$articleEntity->createDate = $this->formatDate('now');
		$articleEntity->createUser = $this->userEntity;
		$articleEntity->status = $values['status'];

		$articleLangEntity = new ArticleLangEntity();
		$articleLangEntity->article = $articleEntity;
		$articleLangEntity->lang = $this->lang;
		$articleLangEntity->title = $values['title'];
		$articleLangEntity->slug = $values['slug'];
		$articleLangEntity->description = $values['description'];
		$articleLangEntity->text = $values['text'];
		$articleLangEntity->editDate = $this->formatDate('now');
		$articleLangEntity->editUser = $this->userEntity;
		
		return $this->articleRepository->create($articleLangEntity);
	}

}
