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
use h4kuna\Gettext\GettextSetup;

class CreateArticleForm extends FormFactory
{	
	/** @var ArticleRepository */
	private $articleRepository;
	
	/** @var UserEntity */
	private $userEntity;
	
	/** @var string */
	private $lang;
    
    /** @var GettextSetup */
    private $gs;
	
	
	public function __construct(
            ArticleRepository $articleRepository, 
            UserRepository $userRepository, 
            User $user,
            GettextSetup $gs
    ) {
		$this->articleRepository = $articleRepository;
		$this->userEntity = $userRepository->get(['id' => $user->id]);
		$this->lang = $articleRepository->lang;
        $this->gs = $gs;
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
			
			$presenter->redirect(':Admin:Article:');
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
			$articleEntity->publishStartDate = \Wame\Utils\Date::toDateTime($values['publish_start_date']);
		}
		if ($values['publish_end_date']) {
			$articleEntity->publishEndDate = \Wame\Utils\Date::toDateTime($values['publish_end_date']);
		}
		$articleEntity->createDate = \Wame\Utils\Date::toDateTime('now');
		$articleEntity->createUser = $this->userEntity;
		$articleEntity->status = $values['status'];
        
//        foreach($this->gs->getLanguages() as $lang => $fullLang) {
		$articleLangEntity = new ArticleLangEntity();
		$articleLangEntity->setEntity($articleEntity);
		$articleLangEntity->lang = $this->lang;
		$articleLangEntity->title = $values['title'];
		$articleLangEntity->slug = $values['slug']?:(Strings::webalize($values['title']));
		$articleLangEntity->description = $values['description'];
		$articleLangEntity->text = $values['text'];
		$articleLangEntity->editDate = \Wame\Utils\Date::toDateTime('now');
		$articleLangEntity->editUser = $this->userEntity;
        
        $articleEntity->addLang($this->lang, $articleLangEntity);
//        }
		
		return $this->articleRepository->create($articleEntity);
	}
}
