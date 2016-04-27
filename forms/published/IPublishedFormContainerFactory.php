<?php

namespace Wame\ArticleModule\Forms;

interface IPublishedFormContainerFactory
{
	/** @return PublishedFormContainer */
	public function create();
	
}