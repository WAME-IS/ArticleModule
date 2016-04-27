<?php

namespace Wame\ArticleModule\Controls;

class ArticleFilterControl extends BaseControl
{	
	
	
	public function render()
	{
		$this->setTemplate('article_filter');
		
		$parameter = $this->presenter->getParameters();
		
		$this->template->sortByName = $this->presenter->link('this', ['sort' => 'name']);
		$this->template->sortByDate = $this->presenter->link('this', array_merge($parameter, ['sort' => 'date']));
		
//		dump($foo);
//		dump($array1);
		
		$this->template->render();
	}
}