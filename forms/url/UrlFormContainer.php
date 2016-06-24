<?php

namespace Wame\ArticleModule\Forms;

use Wame\DynamicObject\Forms\BaseFormContainer;


interface IUrlFormContainerFactory
{
	/** @return UrlFormContainer */
	public function create();
}


class UrlFormContainer extends BaseFormContainer
{
    public function configure() 
	{
		$form = $this->getForm();

		$form->addText('slug', _('URL'))
				->setType('text');
    }


	public function setDefaultValues($object)
	{
		$form = $this->getForm();
		
		$form['slug']->setDefaultValue($object->articleEntity->langs[$object->lang]->slug);
	}

}