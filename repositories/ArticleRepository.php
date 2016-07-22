<?php

namespace Wame\ArticleModule\Repositories;

use Doctrine\ORM\Query\Expr\Join;
use h4kuna\Gettext\GettextSetup;
use Kdyby\Doctrine\EntityManager;
use Nette\DI\Container;
use Nette\Security\User;
use Wame\ArticleModule\Entities\ArticleEntity;
use Wame\ArticleModule\Entities\ArticleLangEntity;
use Wame\Core\Exception\RepositoryException;
use Wame\Core\Repositories\TranslatableRepository;

use Nette\Application\BadRequestException;

class ArticleRepository extends TranslatableRepository
{
    const STATUS_REMOVE = 0;
    const STATUS_PUBLISHED = 1;
    const STATUS_UNPUBLISHED = 2;

    
    public function __construct(
        Container $container, 
        EntityManager $entityManager, 
        GettextSetup $translator,
        User $user
    ) {
        parent::__construct($container, $entityManager, $translator, $user, ArticleEntity::class);
    }

    
    /**
     * Get all status list
     * 
     * @return array
     */
    public function getStatusList()
    {
        return [
            self::STATUS_REMOVE => _('Delete'),
            self::STATUS_PUBLISHED => _('Published'),
            self::STATUS_UNPUBLISHED => _('Unpublished')
        ];
    }

    /**
     * Get one status title
     * 
     * @param int $status
     * @return string
     */
    public function getStatus($status)
    {
        return $this->getStatusList($status);
    }

    /**
     * Publish status list
     * 
     * @return array
     */
    public function getPublishStatusList()
    {
        return [
            self::STATUS_PUBLISHED => _('Published'),
            self::STATUS_UNPUBLISHED => _('Unpublished')
        ];
    }

    /**
     * Add article
     * 
     * @param ArticleEntity $articleEntity		article entity
     * @throws Exception\ArticleNotCreatedException
     */
    public function create($articleEntity)
    {
        $this->entityManager->persist($articleEntity);
        $this->entityManager->persist($articleEntity->langs);
        $this->entityManager->flush();
        return $articleEntity;
    }

    /**
     * Update article
     * 
     * @param ArticleEntity $articleEntity
     * @return ArticleEntity    article
     */
    public function update($articleEntity)
    {
        return $articleEntity;
    }

    /**
     * Delete articles by criteria
     * 
     * @param array $criteria
     * @param int $status
     */
    public function delete($criteria = [], $status = self::STATUS_REMOVE)
    {
        $articleEntity = $this->entity->find($criteria);
        $articleEntity->status = $status;
    }

    /**
     * Get article by criteria
     * return article or exception
     * 
     * @param array $criteria
     * @return ArticleEntity
     * @throws RepositoryException
     */
    public function getArticle($criteria)
    {
        $article = $this->get($criteria);

        if (!$article) {
            throw new BadRequestException(_('Article not found.'));
        }

        if ($article->status != self::STATUS_PUBLISHED) {
            throw new BadRequestException(_('Article is unpublished or removed.'));
        }

        if ($article->publishStartDate != null && strtotime($article->publishStartDate) < time()) {
            throw new BadRequestException(_('Article has not been published.'));
        }

        if ($article->publishEndDate != null && strtotime($article->publishEndDate) > time()) {
            throw new BadRequestException(_('Out of time of article publication.'));
        }

        return $article;
    }
    
    
    /** api *********************************************************** */

    /**
     * @api {get} /article/:id Get article by id
     * @param int $id
     */
    public function getArticleById($id)
    {
        return $this->getArticle(['id' => $id]);
    }

    /**
     * @api {get} /article/ Get all articles
     * @param int $id
     */
    public function find($criteria = [], $orderBy = [], $limit = null, $offset = null)
    {
        return parent::find($criteria, $orderBy, $limit, $offset);
    }

    /**
     * @api {get} /article-search/ Search articles
     * @param array $columns
     * @param string $phrase
     * @param string $select
     */
    public function findLike($columns = [], $phrase = null, $select = '*')
    {
        $search = $this->entityManager->createQueryBuilder()
            ->select($select)
            ->from(ArticleEntity::class, 'a')
            ->leftJoin(ArticleLangEntity::class, 'langs', Join::WITH, 'a.id = langs.article')
            ->andWhere('a.status = :status')
            ->setParameter('status', self::STATUS_PUBLISHED)
            ->andWhere('langs.lang = :lang')
            ->setParameter('lang', $this->lang);

        foreach ($columns as $column) {
            $search->andWhere($column . ' LIKE :phrase');
        }

        $search->setParameter('phrase', '%' . $phrase . '%');

        return $search->getQuery()->getResult();
    }
    
}
