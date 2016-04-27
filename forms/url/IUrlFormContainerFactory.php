<?php

namespace Wame\ArticleModule\Forms;

interface IUrlFormContainerFactory
{
	/** @return UrlFormContainer */
	public function create();
	
}