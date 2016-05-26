<?php

namespace Wame\ArticleModule\Forms;

use Nette\Application\UI\Form;
use Wame\DynamicObject\Forms\BaseFormContainer;

interface ILimitFormContainerFactory
{
	/** @return LimitFormContainer */
	public function create();
}

class LimitFormContainer extends BaseFormContainer
{
    public function render() 
	{
        $this->template->_form = $this->getForm();
        $this->template->render(__DIR__ . '/default.latte');
    }

    public function configure() 
	{
		$form = $this->getForm();

		$form->addText('limit', _('Limit'))
				->setType('number');
    }
	
	public function setDefaultValues($object)
	{
		$form = $this->getForm();
		
		$form['limit']->setDefaultValue($object->componentEntity->getParameter('limit'));
	}
}