<?php

namespace Wame\ArticleModule\Commands;

use Nelmio\Alice\Fixtures\Loader;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Wame\DoctrineFixtures\Contract\Alice\AliceLoaderInterface;

class CreateArticlesCommand extends Command
{
    /** @var AliceLoaderInterface @inject */
    public $aliceLoader;


    /** {@inheritDoc} */
    protected function configure()
    {
        $this
            ->setName('orm:seed-articles:load')
            ->setDescription('Load data fixtures to your database.');
        //->addOption...
    }

    /** {@inheritdoc} */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        try {
            $objects = $this->aliceLoader->load(__DIR__ . '/../fixtures/articles.neon');
            $output->writeln(sprintf('Successfully created "<info>%s</info>" fixtures:', count($objects)));
            foreach($objects as $object) {
                $output->writeln('  - ' . get_class($object));
            }
            return 0; // zero return code means everything is ok
        } catch (\Exception $exc) {
            $output->writeln("<error>{$exc->getMessage()}</error>");
            return 1; // non-zero return code means error
        }
    }

}