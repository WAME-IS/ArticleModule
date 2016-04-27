<?php

namespace Wame\ArticleModule\Forms;

use Nette\Application\UI\Form;
use Wame\DynamicObject\Forms\BaseFormContainer;
use Wame\ArticleModule\Repositories\ArticleRepository;

class StatusFormContainer extends BaseFormContainer
{
	protected $publishStatusList;
	
	public function __construct(ArticleRepository $articleRepository) 
	{
		parent::__construct();
		
		$this->publishStatusList = $articleRepository->getStatusList();
	}
	
    public function render() 
	{
        $this->template->_form = $this->getForm();
        $this->template->render(__DIR__ . '/default.latte');
    }

    public function configure() 
	{
		$form = $this->getForm();

		$form->addRadioList('status', _('Status'), $this->publishStatusList)
				->getSeparatorPrototype()->setName(null);
    }
	
}