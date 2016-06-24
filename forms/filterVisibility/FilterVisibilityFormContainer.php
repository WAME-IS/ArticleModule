<?php

namespace Wame\ArticleModule\Forms;

use Wame\DynamicObject\Forms\BaseFormContainer;


interface IFilterVisibilityFormContainerFactory
{
	/** @return FilterVisibilityFormContainer */
	public function create();
}


class FilterVisibilityFormContainer extends BaseFormContainer
{
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