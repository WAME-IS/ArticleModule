<?php

namespace Wame\ArticleModule\Forms;

use Wame\DynamicObject\Forms\BaseFormContainer;


interface IDescriptionFormContainerFactory
{
	/** @return DescriptionFormContainer */
	public function create();
}


class DescriptionFormContainer extends BaseFormContainer
{
    public function configure() 
	{
		$form = $this->getForm();
		
		$form->addGroup(_('Short description'));
		
		$form->addTextArea('description', _('Description'));
    }


	public function setDefaultValues($object)
	{
		$form = $this->getForm();
		
		$form['description']->setDefaultValue($object->articleEntity->langs[$object->lang]->description);
	}

}