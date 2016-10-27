<?php

namespace Wame\ArticleModule\Vendor\Wame\QuickAddButtonModule\Components\QuickAddButtonControl;

use Nette\Application\LinkGenerator;
use Wame\QuickAddButtonModule\Registers\IQuickAddButton;


interface IArticleFactory
{
    /** @return Article */
    public function create();
}


class Article implements IQuickAddButton
{
	/** @var LinkGenerator */
    private $linkGenerator;


	public function __construct(
        LinkGenerator $linkGenerator
    ) {
        $this->linkGenerator = $linkGenerator;
    }


    /** {@inheritDoc} */
    public function getTitle()
    {
        return _('New article');
    }


    /** {@inheritDoc} */
    public function getIcon()
    {
        return 'note_add';
    }


    /** {@inheritDoc} */
    public function getLink()
    {
        return $this->linkGenerator->link('Admin:Article:create');
    }

}
