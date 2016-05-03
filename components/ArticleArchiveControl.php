<?php

namespace Wame\ArticleModule\Controls;
use Wame\ArticleModule\Repositories\ArticleRepository;

class ArticleArchiveControl extends BaseControl
{	
//	public function __construct() {
//		;
//	}
	
	public function render()
	{
		$this->setTemplate('article_archive');
		
		$articles = $this->articleRepository->find([
				'status' => ArticleRepository::STATUS_PUBLISHED
			]);
		
		$this->template->monthsList = [
			1 => _('January'),
			2 => _('February'),
			3 => _('March'),
			4 => _('April'),
			5 => _('May'),
			6 => _('June'),
			7 => _('July'),
			8 => _('August'),
			9 => _('September'),
			10 => _('October'),
			11 => _('November'),
			12 => _('December')
		];
		
//		$this->template->monthsList = array(1 => _('Január'), 2 => _('Február'), 3 => _('Marec'), 4 => _('Apríl'), 5 => _('Máj'), 6 => _('Jún'), 7 => _('Júl'), 8 => _('August'), 9 => _('September'), 10 => _('Október'), 11 => _('November'), 12 => _('December'));
		
		$list = array();
		foreach($articles as $article) {
			$date = $article->createDate->getTimestamp();
			$year = date('Y', $date);
			$month = date('n', $date);
			if(isset($list[$year][$month])){
				$list[$year][$month] = $list[$year][$month] + 1;
			}else{
				$list[$year][$month] = 1;
			}
		}
		
		$this->template->list = $list;
		
		
//		$this->template->sortByName = $this->presenter->link('this', ['sort' => 'name']);
//		$this->template->sortByDate = $this->presenter->link('this', array_merge($parameter, ['sort' => 'date']));
		
//		dump($foo);
//		dump($array1);
		
		$this->template->render();
	}
}