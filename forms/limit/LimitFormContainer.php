<?php

namespace Wame\ArticleModule\Forms;

use Wame\DynamicObject\Forms\BaseFormContainer;


interface ILimitFormContainerFactory
{
	/** @return LimitFormContainer */
	public function create();
}


class LimitFormContainer extends BaseFormContainer
{
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