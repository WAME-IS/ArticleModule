<?php

namespace Wame\ArticleModule\Components;

use Nette\DI\Container;
use Wame\ArticleModule\Entities\ArticleEntity;
use Wame\ArticleModule\Repositories\ArticleRepository;
use Wame\Core\Components\BaseControl;

interface IArticleControlFactory
{
    /** @return ArticleControl */
    public function create();
}

class ArticleControl extends BaseControl
{
    /** @var integer */
    protected $id;

    /** @var string */
    protected $slug;

    /** @var boolean */
    private $inList = false;

    /** @var ArticleEntity */
    protected $articleEntity;

    /** @var ArticleRepository */
    private $articleRepository;

    /** @var ArticleEntity */
    private $article;

    /** @var string */
    private $lang;
    

    public function __construct(Container $container, ArticleRepository $articleRepository)
    {
        parent::__construct($container);

        $this->articleRepository = $articleRepository;
        $this->lang = $this->articleRepository->lang;

//        $this->getPresenter()->getStatus()->set('meta', 'test');
    }


    /**
     * Render
     * 
     * @param ArticleEntity $articleEntity	article
     */
    public function render($articleEntity)
    {
        if ($articleEntity) {
            $this->article = $articleEntity;
        } else {
            $this->article = $this->getStatus()->get('article');
//            if ($this->id) {
//                $this->article = $this->getArticleById($this->id);
//            } else if ($this->slug) {
//                $this->article = $this->getArticleBySlug($this->slug);
//            }
        }

        $this->template->article = $this->article;
    }
    
    
    /** methods ***************************************************************/

    /**
     * Set id
     * 
     * @param type $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * Set slug
     * 
     * @param type $slug
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;
    }

    /**
     * Set if in list
     * 
     * @param boolean $inList
     */
    public function setInList($inList)
    {
        $this->inList = $inList;
    }
    
    
    /**
     * Get article by ID
     * 
     * @param integer $id		ID
     * @return ArticleEntity	article
     */
    private function getArticleById($id)
    {
        return $this->articleRepository->get([
                'id' => $id,
                'status' => ArticleRepository::STATUS_PUBLISHED
        ]);
    }

    /**
     * Get article by slug
     * 
     * @param string $slug		slug
     * @return ArticleEntity	article
     */
    private function getArticleBySlug($slug)
    {
        return $this->articleRepository->get([
                'langs.slug' => $slug,
                'status' => ArticleRepository::STATUS_PUBLISHED
        ]);
    }
    
}
