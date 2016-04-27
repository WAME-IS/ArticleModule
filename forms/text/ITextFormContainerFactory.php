<?php

namespace Wame\ArticleModule\Forms;

interface ITextFormContainerFactory
{
	/** @return TextFormContainer */
	public function create();
	
}