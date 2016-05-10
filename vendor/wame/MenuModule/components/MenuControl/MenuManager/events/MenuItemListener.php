<?php

namespace Wame\ArticleModule\Vendor\Wame\MenuModule\Components\MenuManager\Events;

use Nette\Object;
use Wame\MenuModule\Repositories\MenuRepository;
use Wame\ArticleModule\Repositories\ArticleRepository;

class MenuItemListener extends Object 
{
	/** @var MenuRepository */
	private $menuRepository;
	
	/** @var ArticleRepository */
	private $articleRepository;
	
	/** @var string */
	private $lang;
	
	
	public function __construct(
		MenuRepository $menuRepository,
		ArticleRepository $articleRepository
	) {
		$this->menuRepository = $menuRepository;
		$this->articleRepository = $articleRepository;
		$this->lang = $menuRepository->lang;
		
		$menuRepository->onCreate[] = [$this, 'onCreate'];
		$menuRepository->onUpdate[] = [$this, 'onUpdate'];
		$menuRepository->onDelete[] = [$this, 'onDelete'];
	}

	
	public function onCreate($form, $values, $menuEntity) 
	{
		if ($menuEntity->type == 'article') {
			$article = $this->articleRepository->getArticle(['id' => $values['value']]);

			$menuEntity->setValue($article->id);

			$menuEntity->langs[$this->lang]->setTitle($article->langs[$this->lang]->title);
			$menuEntity->langs[$this->lang]->setAlternativeTitle($values['alternative_title']);
			$menuEntity->langs[$this->lang]->setSlug($article->langs[$this->lang]->slug);
		}
	}
	
	
	public function onUpdate($form, $values, $menuEntity)
	{
		if ($menuEntity->type == 'article') {
			$article = $this->articleRepository->getArticle(['id' => $values['value']]);

			$menuEntity->setValue($article->id);

			$menuEntity->langs[$this->lang]->setTitle($article->langs[$this->lang]->title);
			$menuEntity->langs[$this->lang]->setAlternativeTitle($values['alternative_title']);
			$menuEntity->langs[$this->lang]->setSlug($article->langs[$this->lang]->slug);
		}
	}
	
	
	public function onDelete()
	{
		
	}

}
