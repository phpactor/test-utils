<?php

namespace Phpactor\TestUtils\Tests\Integration;

use Phpactor\TestUtils\Workspace;
use InvalidArgumentException;

class WorkspaceTest extends IntegrationTestCase
{
    protected function setUp(): void
    {
        $this->workspace = Workspace::create($this->workspaceDir());
        $this->workspace->reset();
    }

    public function testBuild(): void
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

        $this->workspace->loadManifest($manifest);

        $this->assertTrue($this->workspace->exists('Foobar.php'));
        $this->assertTrue($this->workspace->exists('Foobar/Barfoo.php'));
        $this->assertFalse($this->workspace->exists('Foobar/Barboo.php'));
        $this->assertEquals('Hello World', $this->workspace->getContents('Expected.php'));
    }

    public function testGetContentsNotExist(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('File "barbarbarbar" does not exist');

        $this->workspace->getContents('barbarbarbar');
    }

    public function testReset(): void
    {
        $this->workspace->reset();
        touch($this->workspace->path('Foobar.php'));
        mkdir($this->workspace->path('Barfoo'));

        touch($this->workspace->path('Barfoo/Foobar.php'));
        touch($this->workspace->path('Barfoo/BazBoo.php'));

        $this->workspace->reset();

        $this->assertFalse($this->workspace->exists('Foobar.php'));
        $this->assertFalse($this->workspace->exists('Barfoo/Foobar.php'));
        $this->assertFalse($this->workspace->exists('Barfoo/Bazboo.php'));
    }

    public function testMkdir(): void
    {
        $this->workspace->mkdir('foobar');
        $this->assertTrue($this->workspace->exists('foobar'));
        $this->assertFalse($this->workspace->exists('barfoo'));
    }

    public function testPutFileContents(): void
    {
        $this->workspace->put('foobar', 'foobar contents');
        $this->assertTrue($this->workspace->exists('foobar'));
        $this->assertStringContainsString('foobar contents', $this->workspace->getContents('foobar'));
    }

    public function testGetPathWithNoArgs(): void
    {
        $this->assertEquals($this->workspaceDir(), $this->workspace->path());
    }

    public function testGetPath(): void
    {
        $this->assertEquals($this->workspaceDir() . '/foo', $this->workspace->path('foo'));
    }

    public function testGetPathConcat(): void
    {
        $workspace = Workspace::create($this->workspaceDir() . '/foobar/');
        $this->assertEquals($this->workspaceDir() . '/foobar/foo', $workspace->path('foo'));
    }
}
