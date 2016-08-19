<?php

namespace Wame\ArticleModule\Forms;

use Wame\DynamicObject\Forms\BaseFormContainer;


interface IEditorFormContainerFactory
{
	/** @return EditorFormContainer */
	public function create();
}


class EditorFormContainer extends BaseFormContainer
{
    public function configure() 
	{
		$form = $this->getForm();
		
		$form->addEditor('editor', _('Editor'));
    }


	public function setDefaultValues($object)
	{
		$form = $this->getForm();
		
		$form['editor']->setDefaultValue($object->articleEntity->text);
	}

}