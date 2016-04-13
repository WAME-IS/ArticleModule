<?php

namespace Wame\ArticleModule\Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="wame_article")
 * @ORM\Entity
 */
class ArticleEntity extends \Wame\Core\Entities\BaseEntity 
{
	use \Wame\Core\Entities\Columns\Identifier;
	use \Wame\Core\Entities\Columns\CreateDate;
	use \Wame\Core\Entities\Columns\Status;

	/**
     * @ORM\OneToMany(targetEntity="ArticleLangEntity", mappedBy="article")
     */
    protected $lang;
	
	/**
	 * @ORM\Column(name="publish_start_date", type="datetime", nullable=false)
	 */
	protected $publishStartDate = null;

	/**
	 * @ORM\Column(name="publish_end_date", type="datetime", nullable=false)
	 */
	protected $publishEndDate = null;
	
	public $langs;

	public function __construct() {
		parent::__construct();
		
		$langs = new \Doctrine\Common\Collections\ArrayCollection();
		$this->langs = $this->sortByLang($langs);
	}
	
	private function sortByLang($langs)
	{
		$return = [];
		
		foreach ($langs as $lang) {
			$return[$lang->lang] = $lang;
		}
		
		return $return;
	}

}

