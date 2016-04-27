<?php

namespace Wame\ArticleModule\Forms;

interface ITitleFormContainerFactory
{
	/** @return TitleFormContainer */
	public function create();
	
}