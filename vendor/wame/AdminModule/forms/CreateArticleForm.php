<?php

namespace Wame\ArticleModule\Vendor\Wame\AdminModule\Forms;

use Wame\Core\Forms\FormFactory;

class CreateArticleForm extends FormFactory
{	
	public function create()
	{
		$form = $this->createForm();
		
		$form->addSubmit('submit', _('Create article'));

		return $form;
	}

}
