<?php

namespace Wame\ArticleModule\Forms;

interface IStatusFormContainerFactory
{
	/** @return StatusFormContainer */
	public function create();
	
}