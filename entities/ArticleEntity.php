<?php

namespace Wame\ArticleModule\Entities;

use DateTime,
	Doctrine\ORM\Mapping as ORM,
	Wame\Core\Entities\Columns,
	Wame\Core\Entities\TranslatableEntity;

/**
 * @ORM\Table(name="wame_article")
 * @ORM\Entity
 */
class ArticleEntity extends TranslatableEntity
{
	use Columns\Identifier;
	use Columns\CreateDate;
	use Columns\Status;
	
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
	
	
	/**
	 * Add lang
	 * 
	 * @param string $lang
	 * @param object $entity
	 * @return BaseEntity Created language entity
	 */
	public function addLang($lang, $entity = null) {
		if(!$entity) {
			$entity = new ArticleLangEntity();
			$entity->setLang($lang);
			$entity->setArticle($this);
		}
		$this->langs[$lang] = $entity;
		return $entity;
	}
	
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
	
}
