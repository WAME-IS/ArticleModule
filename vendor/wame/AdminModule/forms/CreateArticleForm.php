<?php

namespace Wame\ArticleModule\Vendor\Wame\AdminModule\Forms;

use Nette\Security\User;
use Nette\Application\UI\Form;
use Nette\Utils\Strings;
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
	
	
	public function build()
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
			$articleEntity = $this->create($values);
				
			$this->articleRepository->onCreate($form, $values, $articleEntity);

			$presenter->flashMessage(_('The article was successfully created.'), 'success');
			
			$presenter->redirect('this');
		} catch (\Exception $e) {
			if ($e instanceof \Nette\Application\AbortException) {
				throw $e;
			}
			
			$form->addError($e->getMessage());
		}
	}
	
	/**
	 * Create article
	 * 
	 * @param array $values		values
	 * @return ArticleEntity	article
	 */
	public function create($values)
	{
		$articleEntity = new ArticleEntity();
		if ($values['publish_start_date']) {
			$articleEntity->publishStartDate = $this->formatDate($values['publish_start_date']);
		}
		if ($values['publish_end_date']) {
			$articleEntity->publishEndDate = $this->formatDate($values['publish_end_date']);
		}
		$articleEntity->createDate = $this->formatDate('now');
		$articleEntity->createUser = $this->userEntity;
		$articleEntity->status = $values['status'];

		$articleLangEntity = new ArticleLangEntity();
		$articleLangEntity->article = $articleEntity;
		$articleLangEntity->lang = $this->lang;
		$articleLangEntity->title = $values['title'];
		$articleLangEntity->slug = $values['slug']?:(Strings::webalize($values['title']));
		$articleLangEntity->description = $values['description'];
		$articleLangEntity->text = $values['text'];
		$articleLangEntity->editDate = $this->formatDate('now');
		$articleLangEntity->editUser = $this->userEntity;
		
		return $this->articleRepository->create($articleLangEntity);
	}
}
