<?php

namespace Wame\ArticleModule\Vendor\Wame\AdminModule\Components\AdminMenuControl;

class AdminMenuControlFactory
{	
	/** @var \Nette\Application\LinkGenerator */
	private $linkGenerator;

	public function __construct(
		\Nette\Application\LinkGenerator $linkGenerator
	) {
		$this->linkGenerator = $linkGenerator;
	}

	/** @return AdminMenuControl */
	public function create() 
	{
		return new AdminMenuControl(
			$this->linkGenerator
		);
	}
}
