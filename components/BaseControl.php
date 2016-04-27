<?php

namespace Wame\ArticleModule\Controls;

use Nette\Application\UI\Control;

use Wame\ArticleModule\Repositories\ArticleRepository;

class BaseControl extends Control
{
	/** @var ArticleRepository @inject */
	public $articleRepository;
	
	/** @var ArticleLangRepository @inject */
	public $articleLangRepository;
	
	protected $lang;
	
	public function injectRepository(\Nette\Http\Request $httpRequest, ArticleRepository $articleRepository)
	{
//		dump($this); exit;
//		$this->$httpRequest = $httpRequest;
		$this->articleRepository = $articleRepository;
		$this->lang = $this->articleRepository->lang;
//		
//		dump($this->lang); exit;
	}
	
	public function setTemplate($templateName)
	{
		$this->template->setFile(__DIR__ . '/templates/' . $templateName . '.latte');
		$this->template->lang = $this->lang;
	}
	
}