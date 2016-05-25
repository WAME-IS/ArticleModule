<?php

namespace Wame\ArticleModule\Forms;

use Nette\Application\UI\Form;
use Wame\DynamicObject\Forms\BaseFormContainer;

interface ISortFormContainerFactory
{
	/** @return SortFormContainer */
	public function create();
}

class SortFormContainer extends BaseFormContainer
{
    public function render() 
	{
        $this->template->_form = $this->getForm();
        $this->template->render(__DIR__ . '/default.latte');
    }

    public function configure() 
	{
		$form = $this->getForm();

		$form->addSelect('sort', _('Sort'), [_('Date'), _('Name')]);
		$form->addSelect('order', _('Order'), [_('ASC'), _('DESC')]);
		
//		$form->addText('sort', _('Sort'))
//				->setType('text');
    }
	
	public function setDefaultValues($object)
	{
		$form = $this->getForm();
		
//		$form['slug']->setDefaultValue($object->articleEntity->langs[$object->lang]->slug);
	}
}