<?php

namespace Wame\ArticleModule\Vendor\Wame\AdminModule\Forms;

use Nette\Security\User;
use Nette\Application\UI\Form;
use Nette\Utils\Strings;
use Kdyby\Doctrine\EntityManager;
use Wame\Core\Forms\FormFactory;
use Wame\UserModule\Entities\UserEntity;
use Wame\ArticleModule\Entities\ArticleEntity;
use Wame\ArticleModule\Entities\ArticleLangEntity;
use Wame\UserModule\Repositories\UserRepository;
use Wame\ArticleModule\Repositories\ArticleRepository;

class EditArticleForm extends FormFactory
{	
	/** @var EntityManager */
	private $entityManager;
	
	/** @var ArticleRepository */
	private $articleRepository;
	
	/** @var UserRepository */
	private $userRepository;
	
	/** @var UserEntity */
	private $userEntity;
	
	/** @var ArticleEntity */
	public $articleEntity;
	
	/** @var string */
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
            \Wame\Utils\Exception::handleFormException($e, $form);
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
	private function update($articleId, $values = [])
	{
		$articleEntity = $this->articleRepository->get(['id' => $articleId]);
		if ($values['publish_start_date']) {
			$articleEntity->publishStartDate = \Wame\Utils\Date::toDateTime($values['publish_start_date']);
		} else {
			$articleEntity->publishStartDate = null;
		}
		if ($values['publish_end_date']) {
			$articleEntity->publishEndDate = \Wame\Utils\Date::toDateTime($values['publish_end_date']);
		} else {
			$articleEntity->publishEndDate = null;
		}
		$articleEntity->status = $values['status'];
		
        /* @var $articleLangEntity ArticleLangEntity */
        $articleLangEntity = $articleEntity->langs[$this->lang];
//		$articleLangEntity = $this->entityManager->getRepository(ArticleLangEntity::class)->findOneBy(['article' => $articleEntity, 'lang' => $this->lang]);
		$articleLangEntity->setTitle($values['title']);
		$articleLangEntity->setSlug($values['slug']?:(Strings::webalize($values['title'])));
		$articleLangEntity->setDescription($values['description']);
		$articleLangEntity->setText($values['text']);
		$articleLangEntity->setEditDate(\Wame\Utils\Date::toDateTime('now'));
		$articleLangEntity->setEditUser($this->userEntity);
		
		return $this->articleRepository->update($articleEntity);
	}

}