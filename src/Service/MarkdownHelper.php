<?php

namespace App\Service;


use Knp\Bundle\MarkdownBundle\MarkdownParserInterface;
use Psr\Log\LoggerInterface;
use Symfony\Contracts\Cache\CacheInterface;

class MarkdownHelper{

    private $markdownParser;
    private $cache;
    private $isDebug;
    private $logger;

    public function __construct(MarkdownParserInterface $markdownParser, CacheInterface $cache, bool $isDebug, LoggerInterface $mdLogger)
    {
        $this->markdownParser = $markdownParser;
        $this->cache = $cache;
        $this->isDebug = $isDebug;
        $this->logger = $debugLogger;
    }

    public function parse(string $source): string {

        if ($this->isDebug){
            $this->logger->info('Je ne suis pas en cache');
            return $this->markdownParser->transformMarkdown($source);
        }

        $this->logger->info('Je suis en cache');
        return $this->cache->get('markdown_' . md5($source), function () use ($source) {
            return $this->markdownParser->transformMarkdown($source);
        });
    }

    public function getStrong(string $source): string {
        return '<strong>' . $source . '</strong>';
    }
}