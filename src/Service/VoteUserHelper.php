<?php

namespace App\Service;


class VoteUserHelper{

    private $markdownParser;
    private $cache;
    private $isDebug;
    private $logger;

    public function __construct(MarkdownParserInterface $markdownParser, CacheInterface $cache, bool $isDebug, LoggerInterface $debugLogger)
    {
        $this->markdownParser = $markdownParser;
        $this->cache = $cache;
        $this->isDebug = $isDebug;
        $this->logger = $debugLogger;
    }



    public function getStrong(string $source): string {
        return '<strong>' . $source . '</strong>';
    }
}