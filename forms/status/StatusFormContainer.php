<?php

namespace Wame\ArticleModule\Forms;

use Wame\DynamicObject\Forms\BaseFormContainer;
use Wame\ArticleModule\Repositories\ArticleRepository;


interface IStatusFormContainerFactory
{
	/** @return StatusFormContainer */
	public function create();
}


class StatusFormContainer extends BaseFormContainer
{
	protected $publishStatusList;


	public function __construct(ArticleRepository $articleRepository) 
	{
		parent::__construct();
		
		$this->publishStatusList = $articleRepository->getPublishStatusList();
	}


    public function configure() 
	{
		$form = $this->getForm();

		$form->addRadioList('status', _('Status'), $this->publishStatusList)
				->getSeparatorPrototype()->setName(null);
    }


	public function setDefaultValues($object)
	{
		$form = $this->getForm();
		
		$form['status']->setDefaultValue($object->articleEntity->status);
	}

}