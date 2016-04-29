<?php

namespace Wame\ArticleModule\Forms;

use Wame\DynamicObject\Forms\BaseFormContainer;

interface ITextFormContainerFactory
{
	/** @return TextFormContainer */
	public function create();
}

class TextFormContainer extends BaseFormContainer
{
    public function render() 
	{
        $this->template->_form = $this->getForm();
        $this->template->render(__DIR__ . '/default.latte');
    }

    public function configure() 
	{
		$form = $this->getForm();
		
		$form->addGroup(_('Text'));
		
		$form->addTextArea('text', _('Text'));
    }
	
	public function setDefaultValues($object)
	{
		$form = $this->getForm();
		
		$form['text']->setDefaultValue($object->articleEntity->langs[$object->lang]->text);
	}
}