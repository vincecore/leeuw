<?php
/**
 * {project-name}
 *
 * @author {author-name}
 */
declare(strict_types=1);

namespace App\Command;

use App\Jobs\IndexProductsInElasticsearch;
use Spiral\Console\Command;
use Spiral\Queue\QueueInterface;

class IndexElasticSearchCommand extends Command
{
    protected const NAME = 'leeuw:index-elastic-search';

    protected const DESCRIPTION = '';

    protected const ARGUMENTS = [];

    protected const OPTIONS = [];


    /**
     * Perform command
     */
    protected function perform(QueueInterface $queue): void
    {
        $queue->push(IndexProductsInElasticsearch::class);
    }
}
