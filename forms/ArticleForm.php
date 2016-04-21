<?php

namespace Wame\ArticleModule\Forms;

use Wame\Core\Forms\FormFactory;
use Wame\ArticleModule\Repositories\ArticleRepository;

class ArticleForm extends FormFactory
{
	/** @var array */
	private $publishStatusList;
	
	public function __construct(
		ArticleRepository $articleRepository
	) {
		$this->publishStatusList = $articleRepository->getPublishStatusList();
	}

	public function create()
	{
		$form = $this->createForm();
		
		$form->addGroup(_('Basic info'));
		
		$form->addText('title', _('Title'))
				->setRequired(_('Please enter title'));

		$form->addText('slug', _('URL'))
				->setRequired(_('Please enter url'));

		$form->addGroup(_('Publish info'));
		
		$form->addText('publish_start_date', _('Published from'));

		$form->addText('publish_end_date', _('Published to'));
		
		$form->addRadioList('status', _('Status'), $this->publishStatusList)
				->getSeparatorPrototype()->setName(null);

		$form->addGroup(_('Short description'));
		
		$form->addTextArea('description', _('Description'));

		$form->addGroup(_('Text'));
		
		$form->addTextArea('text', _('Text'));

		$form->addSubmit('submit', _('Submit'));
		
		return $form;
	}

}
