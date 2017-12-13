<?php

namespace Wame\ArticleModule\Vendor\Wame\MenuModule\Components\MenuManager\Forms;

use Nette\Application\LinkGenerator;
use Wame\MenuModule\Models\Item;
use Wame\MenuModule\Models\DatabaseMenuProvider\IMenuItem;
use Wame\MenuModule\Repositories\MenuRepository;

interface IArticleMenuItem
{
	/** @return ArticleMenuItem */
	public function create();
}


class ArticleMenuItem implements IMenuItem
{	
    /** @var LinkGenerator */
	private $linkGenerator;
	
    /** @var string */
	private $lang;
	
	
	public function __construct(
		LinkGenerator $linkGenerator,
		MenuRepository $menuRepository
	) {
		$this->linkGenerator = $linkGenerator;
		$this->lang = $menuRepository->lang;
	}

	
	public function addItem()
	{
		$item = new Item();
		$item->setName($this->getName());
		$item->setTitle($this->getTitle());
		$item->setDescription($this->getDescription());
		$item->setLink($this->getLinkCreate());
		$item->setIcon($this->getIcon());
		
		return $item->getItem();
	}

	
	public function getName()
	{
		return 'article';
	}
	
	
	public function getTitle()
	{
		return _('Article');
	}
	
	
	public function getDescription()
	{
		return _('Insert link to the article');
	}
	
	
	public function getIcon()
	{
		return 'fa fa-file-text';
	}
	
	
	public function getLinkCreate($menuId = null)
	{
		return $this->linkGenerator->link('Admin:Article:menuItem', ['m' => $menuId]);
	}
	
	
	public function getLinkUpdate($menuEntity)
	{
		return $this->linkGenerator->link('Admin:Article:menuItem', ['id' => $menuEntity->getId(), 'm' => $menuEntity->getComponent()->getId()]);
	}
	
	
	public function getLink($menuEntity)
	{
		return $this->linkGenerator->link('Article:Article:show', ['id' => $menuEntity->getValue(), 'lang' => $this->lang]);
	}
	
}
