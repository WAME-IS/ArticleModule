<?php

namespace Wame\ArticleModule\Forms;

use Nette\Application\UI\Form;
use Wame\DynamicObject\Forms\BaseFormContainer;

interface IFilterVisibilityFormContainerFactory
{
	/** @return FilterVisibilityFormContainer */
	public function create();
}

class FilterVisibilityFormContainer extends BaseFormContainer
{
    public function render() 
	{
        $this->template->_form = $this->getForm();
        $this->template->render(__DIR__ . '/default.latte');
    }

    public function configure() 
	{
		$form = $this->getForm();

		$form->addCheckbox('filter_visibility', _('Filter visible'));
    }
	
	public function setDefaultValues($object)
	{
		$form = $this->getForm();
		
		$form['filter_visibility']->setDefaultValue($object->componentEntity->getParameter('filter_visibility'));
	}
}