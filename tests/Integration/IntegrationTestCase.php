<?php

namespace Phpactor\TestUtils\Tests\Integration;

use Phpactor\TestUtils\PHPUnit\TestCase;

class IntegrationTestCase extends TestCase
{
    protected function workspaceDir(): string
    {
        return realpath(__DIR__ . '/..') . '/Workspace';
    }
}
