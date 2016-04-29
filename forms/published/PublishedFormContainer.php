<?php

namespace Wame\ArticleModule\Forms;

use Wame\DynamicObject\Forms\BaseFormContainer;

interface IPublishedFormContainerFactory
{
	/** @return PublishedFormContainer */
	public function create();
}

class PublishedFormContainer extends BaseFormContainer
{
    public function render() 
	{
        $this->template->_form = $this->getForm();
        $this->template->render(__DIR__ . '/default.latte');
    }

    public function configure() 
	{
		$form = $this->getForm();
		
		$form->addGroup(_('Publish info'));

		$form->addText('publish_start_date', _('Published from'));
		
		$form->addText('publish_end_date', _('Published to'));
    }
	
}