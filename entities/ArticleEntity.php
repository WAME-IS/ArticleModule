<?php

namespace Wame\ArticleModule\Entities;

use Doctrine\ORM\Mapping as ORM;
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
	use Columns\PublishDate;

	/**
	 * @ORM\OneToMany(targetEntity="ArticleLangEntity", mappedBy="article", cascade={"persist"})
	 */
	protected $langs;


	public function getCreateUserId()
	{
		return $this->getCreateUser()->id;
	}

}