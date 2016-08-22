<?php

namespace Wame\ArticleModule\Entities;

use Doctrine\ORM\Mapping as ORM;
use Wame\Core\Entities\BaseEntity;
use Wame\Core\Entities\Columns;
use Wame\RestApiModule\DataConverter\Annotations\noApi;

/**
 * @ORM\Table(name="wame_article_lang")
 * @ORM\Entity
 */
class ArticleLangEntity extends BaseEntity 
{
 	use Columns\Identifier;
	use Columns\Description;
	use Columns\EditDate;
	use Columns\EditUser;
	use Columns\Lang;
	use Columns\Slug;
	use Columns\Title;

	/**
     * @noApi
     * @ORM\ManyToOne(targetEntity="ArticleEntity", inversedBy="langs", fetch="LAZY")
     * @ORM\JoinColumn(name="article_id", referencedColumnName="id", nullable=false)
     */
	protected $article;

	/**
	 * @ORM\Column(name="text", type="text", length=65535, nullable=false)
	 */
	protected $text;

	
	/** get ************************************************************/

	public function getText()
	{
		return $this->text;
	}

	
	/** set ************************************************************/

	public function setText($text)
	{
		$this->text = $text;
		 
		return $this;
	}
	
}

