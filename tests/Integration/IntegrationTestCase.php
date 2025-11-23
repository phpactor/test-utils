<?php

namespace Phpactor\TestUtils\Tests\Integration;

use PHPUnit\Framework\TestCase;

class IntegrationTestCase extends TestCase
{
    protected function workspaceDir(): string
    {
        return realpath(__DIR__ . '/..') . '/Workspace';
    }
}
