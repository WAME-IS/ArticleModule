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
    protected $langs;
	
	/**
	 * @ORM\Column(name="publish_start_date", type="datetime", nullable=true)
	 */
	protected $publishStartDate;

	/**
	 * @ORM\Column(name="publish_end_date", type="datetime", nullable=true)
	 */
	protected $publishEndDate;
	
	
//	public function getPublishStartDate()
//	{
//		return $this->publishStartDate;
//	}

}