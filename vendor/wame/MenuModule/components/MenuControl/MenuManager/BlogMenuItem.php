<?php

namespace Wame\ArticleModule\Vendor\Wame\MenuModule\Components\MenuManager\Forms;

use Nette\Application\LinkGenerator;
use Wame\MenuModule\Models\Item;
use Wame\MenuModule\Models\DatabaseMenuProvider\IMenuItem;
use Wame\MenuModule\Repositories\MenuRepository;

interface IBlogMenuItem
{
	/** @return BlogMenuItem */
	public function create();
}


class BlogMenuItem implements IMenuItem
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

	/** {@inheritDoc} */
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

	/** {@inheritDoc} */
	public function getName()
	{
		return 'blog';
	}
	
	/** {@inheritDoc} */
	public function getTitle()
	{
		return _('Blog');
	}
	
	/** {@inheritDoc} */
	public function getDescription()
	{
		return _('Insert link to the blog');
	}
	
	/** {@inheritDoc} */
	public function getIcon()
	{
		return 'fa fa-file-text';
	}
	
	/** {@inheritDoc} */
	public function getLinkCreate($menuId = null)
	{
		return $this->linkGenerator->link('Admin:Blog:menuItem', ['m' => $menuId]);
	}
	
	/** {@inheritDoc} */
	public function getLinkUpdate($menuEntity)
	{
		return $this->linkGenerator->link('Admin:Blog:menuItem', ['id' => $menuEntity->id, 'm' => $menuEntity->component->id]);
	}
	
	/** {@inheritDoc} */
	public function getLink($menuEntity)
	{
		return $this->linkGenerator->link('Article:Article:default', ['id' => null, 'lang' => $this->lang]);
	}
	
}
