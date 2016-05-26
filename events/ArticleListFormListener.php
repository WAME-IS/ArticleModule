<?php

namespace Wame\ArticleModule\Events;

use Nette\Object;
use Wame\Utils\Form\Helpers;
use Wame\ComponentModule\Repositories\ComponentRepository;
use Wame\SliderBootstrapCarouselPlugin\Vendor\Wame\SliderModule\Components\SliderControl\BootstrapCarousel;

class ArticleListFormListener extends Object 
{
	/** @var ComponentRepository */
	private $componentRepository;
	

	public function __construct(
		ComponentRepository $componentRepository
	) {
		$this->componentRepository = $componentRepository;
		
		$componentRepository->onCreate[] = [$this, 'onCreate'];
		$componentRepository->onUpdate[] = [$this, 'onUpdate'];
		$componentRepository->onDelete[] = [$this, 'onDelete'];
	}

	
	public function onCreate($form, $values, $componentEntity) 
	{
		if ($componentEntity->type == 'ArticleListComponent') {				
			$componentEntity->setParameters($this->getParams($values, $componentEntity->getParameters()));
		}
	}
	
	
	public function onUpdate($form, $values, $componentEntity)
	{
		if ($componentEntity->type == 'ArticleListComponent') {
			$componentEntity->setParameters($this->getParams($values, $componentEntity->getParameters()));
		}
	}
	
	
	public function onDelete()
	{
		
	}
	
	
	/**
	 * Get parameters
	 * 
	 * @param array $values
	 * @param array $parameters
	 * @return array
	 */
	private function getParams($values, $parameters = [])
	{
		$array = [
			'sort' => $values->sort,
			'order' => $values->order,
			'limit' => $values->limit,
			'paginator_visibility' => $values->paginator_visibility,
			'filter_visibility' => $values->filter_visibility,
		];
		
		return array_replace($parameters, $array);
	}

}
