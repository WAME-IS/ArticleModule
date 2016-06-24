<?php

namespace Wame\ArticleModule\Forms;

use Wame\DynamicObject\Forms\BaseFormContainer;


interface IPaginatorVisibilityFormContainerFactory
{
	/** @return PaginatorVisibilityFormContainer */
	public function create();
}


class PaginatorVisibilityFormContainer extends BaseFormContainer
{
    public function configure() 
	{
		$form = $this->getForm();

		$form->addCheckbox('paginator_visibility', _('Paginator visible'));
    }


	public function setDefaultValues($object)
	{
		$form = $this->getForm();
		
		$form['paginator_visibility']->setDefaultValue($object->componentEntity->getParameter('paginator_visibility'));
	}

}