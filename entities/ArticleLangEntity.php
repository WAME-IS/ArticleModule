<?php

namespace Wame\ArticleModule\Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="wame_article_lang", indexes={@ORM\Index(name="article_id", columns={"article"})})
 * @ORM\Entity
 */
class ArticleLangEntity extends \Wame\Core\Entities\BaseEntity 
{
	use \Wame\Core\Entities\Columns\Identifier;
	use \Wame\Core\Entities\Columns\EditDate;

	/**
     * @ORM\ManyToOne(targetEntity="ArticleEntity", inversedBy="lang")
     * @ORM\JoinColumn(name="article_id", referencedColumnName="id", nullable=false)
     */
	protected $article;
	
	/**
	 * @ORM\Column(name="lang", type="string", length=2, nullable=true)
	 */
	protected $lang;
	
	/**
	 * @ORM\Column(name="title", type="string", length=250, nullable=true)
	 */
	protected $title;

	/**
	 * @ORM\Column(name="slug", type="string", length=250, nullable=true)
	 */
	protected $slug;

	/**
	 * @ORM\Column(name="description", type="string", length=255, nullable=false)
	 */
	protected $description;

	/**
	 * @ORM\Column(name="text", type="string", nullable=false)
	 */
	protected $text;

}

