<?php

namespace Fixture;

use Fixture\Logger;

class ErrorHandler
{
    private $logger;

    public function __construct(Logger $logger)
    {
        $this->setLogger($logger);
    }

    public function setLogger(Logger $logger)
    {
        $this->logger = $logger;

        return $this;
    }

    public function getLogger()
    {
        return $this->logger;
    }
}
