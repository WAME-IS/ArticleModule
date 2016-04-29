<?php

namespace Wame\ArticleModule\Vendor\Wame\AdminModule\Forms;

use Nette\Security\User;
use Nette\Application\UI\Form;
use Wame\Core\Forms\FormFactory;
use Kdyby\Doctrine\EntityManager;
use Wame\UserModule\Entities\UserEntity;
use Wame\ArticleModule\Entities\ArticleEntity;
use Wame\ArticleModule\Entities\ArticleLangEntity;
use Wame\UserModule\Repositories\UserRepository;
use Wame\ArticleModule\Repositories\ArticleRepository;

class EditArticleForm extends FormFactory
{	
	/** @var EntityManager */
	private $entityManager;
	
	/** @val ArticleRepository */
	private $articleRepository;
	
	/** @val UserRepository */
	private $userRepository;
	
	/** @val UserEntity */
	private $userEntity;
	
	/** @val ArticleEntity */
	public $articleEntity;
	
	/** @val string */
	public $lang;
	
	
	public function __construct(EntityManager $entityManager, ArticleRepository $articleRepository, UserRepository $userRepository, User $user)
	{
		parent::__construct();
		
		$this->entityManager = $entityManager;
		$this->articleRepository = $articleRepository;
		$this->userEntity = $userRepository->get(['id' => $user->id]);
		$this->lang = $articleRepository->lang;
	}
	
	
	public function build()
	{
		$form = $this->createForm();

		$form->addSubmit('submit', _('Edit article'));
		
		if ($this->id) {
			$this->articleEntity = $this->articleRepository->get(['id' => $this->id]);
			$this->setDefaultValues();
		}
		
		$form->onSuccess[] = [$this, 'formSucceeded'];

		return $form;
	}
	
	public function formSucceeded(Form $form, $values)
	{
		$presenter = $form->getPresenter();
		
		try {
			$articleEntity = $this->update($presenter->id, $values);
		
			$this->articleRepository->onUpdate($form, $values, $articleEntity);

			$presenter->flashMessage(_('The article was successfully updated.'), 'success');
			
			$presenter->redirect('this');
		} catch (\Exception $e) {
			if ($e instanceof \Nette\Application\AbortException) {
				throw $e;
			}
			
			$form->addError($e->getMessage());
			$this->entityManager->clear();
		}
	}
	
	/**
	 * Update article
	 * 
	 * @param integer $articleId	article ID
	 * @param array $values			values
	 * @return ArticleEntity		article
	 */
	public function update($articleId, $values)
	{
		$articleEntity = $this->articleRepository->get(['id' => $articleId]);

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
		
		return $this->articleRepository->update($articleLangEntity);
	}

}