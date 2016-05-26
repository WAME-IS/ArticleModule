<?php

namespace Wame\ArticleModule\Forms;

use Nette\Application\UI\Form;
use Wame\DynamicObject\Forms\BaseFormContainer;

interface IPaginatorVisibilityFormContainerFactory
{
	/** @return PaginatorVisibilityFormContainer */
	public function create();
}

class PaginatorVisibilityFormContainer extends BaseFormContainer
{
    public function render() 
	{
        $this->template->_form = $this->getForm();
        $this->template->render(__DIR__ . '/default.latte');
    }

    public function configure() 
	{
		$form = $this->getForm();

		$form->addCheckbox('paginator_visibility', _('Paginator visible'));
    }
	
	public function setDefaultValues($object)
	{
		$form = $this->getForm();
		
//		$form['limit']->setDefaultValue($object->articleEntity->langs[$object->lang]->slug);
	}
}