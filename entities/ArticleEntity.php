<?php

namespace Wame\ArticleModule\Entities;

use DateTime,
	Doctrine\ORM\Mapping as ORM,
	Wame\Core\Entities\BaseEntity;

/**
 * @ORM\Table(name="wame_article")
 * @ORM\Entity
 */
class ArticleEntity extends BaseEntity {

	use \Wame\Core\Entities\Columns\Identifier;
	use \Wame\Core\Entities\Columns\CreateDate;
	use \Wame\Core\Entities\Columns\Status;

	/**
	 * @ORM\OneToMany(targetEntity="ArticleLangEntity", mappedBy="article")
	 */
	protected $langs;

	/**
	 * @var DateTime
	 * @ORM\Column(name="publish_start_date", type="datetime", nullable=true)
	 */
	protected $publishStartDate;

	/**
	 * @var DateTime
	 * @ORM\Column(name="publish_end_date", type="datetime", nullable=true)
	 */
	protected $publishEndDate;

//	public function getPublishStartDate()
//	{
//		return $this->publishStartDate;
//	}
}
