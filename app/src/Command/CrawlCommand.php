<?php
/**
 * {project-name}
 *
 * @author {author-name}
 */
declare(strict_types=1);

namespace App\Command;

use App\Jobs\GetAllProducts;
use Spiral\Console\Command;
use Spiral\Queue\QueueInterface;

class CrawlCommand extends Command
{
    protected const NAME = 'leeuw:crawl';

    protected const DESCRIPTION = '';

    protected const ARGUMENTS = [];

    protected const OPTIONS = [];


    /**
     * Perform command
     */
    protected function perform(QueueInterface $queue): void
    {
        $queue->push(GetAllProducts::class);
    }
}
