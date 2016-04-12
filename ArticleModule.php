<?php

namespace Wame;

use Wame\Core\Models\Plugin;
use Wame\PermissionModule\Models\PermissionObject;

class ArticleModule extends Plugin 
{
	/** @var PermissionObject */
	private $permission;

	public function __construct(PermissionObject $permission) 
	{
		$this->permission = $permission;
	}
	
	public function onEnable() 
	{
		$this->permission->addResource('article');
		$this->permission->addResourceAction('article', 'view');
		$this->permission->allow('guest', 'article', 'view');
		$this->permission->addResourceAction('article', 'add');
		$this->permission->allow('moderator', 'article', 'add');
		$this->permission->addResourceAction('article', 'edit');
		$this->permission->allow('moderator', 'article', 'edit');
		$this->permission->addResourceAction('article', 'delete');
		$this->permission->allow('moderator', 'article', 'delete');
	}
	
}
