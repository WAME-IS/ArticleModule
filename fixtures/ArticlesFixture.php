<?php

namespace Wame\ArticleModule\Fixtures;

use Doctrine\Common\Persistence\ObjectManager;
use Nette\Security\Passwords;
use Wame\ArticleModule\Entities\ArticleEntity;
use Wame\ArticleModule\Entities\ArticleLangEntity;
use Wame\ArticleModule\Repositories\ArticleRepository;
use Wame\UserModule\Entities\UserEntity;

class ArticlesFixture extends \Doctrine\Common\DataFixtures\AbstractFixture
{
    /** @var ArticleRepository @inject */
    public $articleRepository;


    public function load(ObjectManager $manager)
    {
        $user = $manager->getRepository(UserEntity::class)->findOneBy(['id' => 1]);

        $faker = \Faker\Factory::create('sk_SK');

        $article = new ArticleEntity();
        $article->setStatus(1);
        $article->setCreateUser($user);

        $articleLang = new ArticleLangEntity();
        $articleLang->setTitle("test");
        $articleLang->setText($faker->text());

        $article->addLang("sk", $articleLang);

        $manager->persist($article);
        $manager->persist($articleLang);

        $manager->flush();
    }

}