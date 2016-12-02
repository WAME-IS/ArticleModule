<?php

namespace Wame\ArticleModule\Entities;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Wame\Core\Entities\BaseEntity;
use Wame\Core\Entities\Columns;
use Wame\LanguageModule\Entities\TranslatableEntity;

/**
 * @ORM\Table(name="wame_article")
 * @ORM\Entity
 */
class ArticleEntity extends TranslatableEntity
{
	use Columns\Identifier;
	use Columns\CreateDate;
	use Columns\CreateUser;
	use Columns\Status;

	/**
	 * @ORM\OneToMany(targetEntity="ArticleLangEntity", mappedBy="article", cascade={"persist"})
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


	/** get ************************************************************/

	public function getPublishStartDate()
	{
		return $this->publishStartDate;
	}

	public function getPublishEndDate()
	{
		return $this->publishEndDate;
	}


	/** set ************************************************************/

	public function setPublishStartDate($publishStartDate)
	{
		$this->publishStartDate = $publishStartDate;

		return $this;
	}

	public function setPublishEndDate($publishEndDate)
	{
		$this->publishEndDate = $publishEndDate;

		return $this;
	}

	public function getCreateUserId()
	{
		return $this->getCreateUser()->id;
	}

}
