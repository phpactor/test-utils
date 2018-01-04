<?php

namespace Phpactor\TestUtils\Tests\Integration\Workspace;

use Phpactor\TestUtils\WorkspaceInitializer;
use Phpactor\TestUtils\Tests\Integration\IntegrationTestCase;
use Phpactor\TestUtils\Workspace;
use InvalidArgumentException;

class WorkspaceTest extends IntegrationTestCase
{
    public function testBuild()
    {
        $manifest = <<<'EOT'
// File: Foobar.php
<?php

class Foobar
{
}
// File: Foobar/Barfoo.php
<?php

namespace Foobar;

class Barfoo
{
}
// File: Expected.php
Hello World
EOT
        ;

        $workspace = Workspace::create($this->workspaceDir());
        $workspace->loadManifest($manifest);

        $this->assertTrue($workspace->exists('Foobar.php'));
        $this->assertTrue($workspace->exists('Foobar/Barfoo.php'));
        $this->assertFalse($workspace->exists('Foobar/Barboo.php'));
        $this->assertEquals('Hello World', $workspace->getContents('Expected.php'));
    }

    public function testGetContentsNotExist()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('File "barbarbarbar" does not exist');

        $workspace = Workspace::create($this->workspaceDir());
        $workspace->getContents('barbarbarbar');
    }

    public function testReset()
    {
        $workspace = Workspace::create($this->workspaceDir());
        $workspace->reset();
        touch($workspace->path('Foobar.php'));
        mkdir($workspace->path('Barfoo'));

        touch($workspace->path('Barfoo/Foobar.php'));
        touch($workspace->path('Barfoo/BazBoo.php'));

        $workspace->reset();

        $this->assertFalse($workspace->exists('Foobar.php'));
        $this->assertFalse($workspace->exists('Barfoo/Foobar.php'));
        $this->assertFalse($workspace->exists('Barfoo/Bazboo.php'));
    }
}
