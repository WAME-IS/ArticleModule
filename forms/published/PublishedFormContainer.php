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
    public function configure() 
	{
		$form = $this->getForm();
		
		$form->addGroup(_('Publish info'));

		$form->addText('publish_start_date', _('Published from'));
		
		$form->addText('publish_end_date', _('Published to'));
    }


	public function setDefaultValues($object)
	{
		$form = $this->getForm();
		
		$form['publish_start_date']->setDefaultValue($this->formatDate($object->articleEntity->publishStartDate));
		$form['publish_end_date']->setDefaultValue($this->formatDate($object->articleEntity->publishEndDate));
	}

}