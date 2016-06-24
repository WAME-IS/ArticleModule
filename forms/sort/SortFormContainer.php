<?php

namespace Wame\ArticleModule\Forms;

use Wame\DynamicObject\Forms\BaseFormContainer;


interface ISortFormContainerFactory
{
	/** @return SortFormContainer */
	public function create();
}


class SortFormContainer extends BaseFormContainer
{
    public function configure() 
	{
		$form = $this->getForm();

		$form->addSelect('sort', _('Sort'), ['Date' => _('Date'), 'Name' => _('Name')]);
		$form->addSelect('order', _('Order'), ['ASC' => _('ASC'), 'DESC' => _('DESC')]);
    }


	public function setDefaultValues($object)
	{
		$form = $this->getForm();
		
		$form['sort']->setDefaultValue($object->componentEntity->getParameter('sort'));
		$form['order']->setDefaultValue($object->componentEntity->getParameter('order'));
	}

}