<?php

namespace Wame\ArticleModule\Repositories;

use Nette\Security\User;
use Wame\ArticleModule\Entities\ArticleLangEntity;
use Wame\UserModule\Entities\UserEntity;

class ArticleLangRepository extends \Wame\Core\Repositories\BaseRepository
{
	const TABLE_NAME = 'article_lang';
	
	/** @var UserEntity */
	private $userEntity;
	
	public function __construct(
		\Nette\DI\Container $container, 
		\Kdyby\Doctrine\EntityManager $entityManager,
		User $user
	) {
		parent::__construct($container, $entityManager, self::TABLE_NAME);

		$this->userEntity = $this->entityManager->getRepository(UserEntity::class)->findOneBy(['id' => $user->id]);
	}
	
	public function add($article, $values)
	{		
		$articleLangEntity = new ArticleLangEntity();
		
		$articleLangEntity->article = $article;
		$articleLangEntity->lang = 'sk';
		$articleLangEntity->title = $values['title'];
		$articleLangEntity->slug = $values['slug'];
		$articleLangEntity->description = $values['description'];
		$articleLangEntity->text = $values['text'];
		$articleLangEntity->editDate = new \DateTime('now');
		$articleLangEntity->editUser = $this->userEntity;
		
		return $articleLangEntity;
	}
	
}