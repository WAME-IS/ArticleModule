<?php

namespace Wame\ArticleModule\Vendor\Wame\MenuModule\Components\MenuManager\Forms;

use Wame\DynamicObject\Forms\BaseFormContainer;

interface IArticleFormContainerFactory
{
	/** @return ArticleFormContainer */
	public function create();
}


class ArticleFormContainer extends BaseFormContainer
{
    public function render() 
	{
        $this->template->_form = $this->getForm();
        $this->template->render(__DIR__ . '/default.latte');
    }

	
    public function configure() 
	{
		$form = $this->getForm();
		
		$form->addText('value', _('Value'));
		
		$form->addText('alternative_title', _('Alternative title'));
    }
	
	
	public function setDefaultValues($object)
	{
		$form = $this->getForm();

		$form['value']->setDefaultValue($object->menuEntity->value);
		$form['alternative_title']->setDefaultValue($object->menuEntity->langs[$object->lang]->alternativeTitle);
	}

}