<?php

namespace App\AdminModule\Presenters;

use Wame\ArticleModule\Entities\ArticleEntity;
use Wame\ArticleModule\Repositories\ArticleRepository;

class ArticlesPresenter extends \App\AdminModule\Presenters\BasePresenter
{	
	/** @var ArticleEntity */
	private $articleEntity;
	
	public function startup() 
	{
		parent::startup();
		
		if (!$this->user->isAllowed('article', 'view')) {
			$this->flashMessage(_('To enter this section you have sufficient privileges.'), 'danger');
			$this->redirect('parent');
		}
		
		$this->articleEntity = $this->entityManager->getRepository(ArticleEntity::class);
	}

	public function renderDefault()
	{
		$this->template->siteTitle = _('Articles');
		$this->template->articleEntity = $this->articleEntity->findBy(['status NOT IN (?)' => [ArticleRepository::STATUS_REMOVE]]);
	}
}
