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
		
		$form->addEditor('text', _('Text'));
    }


	public function setDefaultValues($object)
	{
		$form = $this->getForm();
		
		$form['text']->setDefaultValue($object->articleEntity->text);
	}

}