<?php

namespace Wame\ArticleModule\Forms;

interface IDescriptionFormContainerFactory
{
	/** @return DescriptionFormContainer */
	public function create();
	
}