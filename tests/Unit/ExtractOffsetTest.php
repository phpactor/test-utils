<?php

namespace Phpactor\TestUtils\Tests\Unit;

use Phpactor\TestUtils\ExtractOffset;
use Phpactor\TestUtils\PHPUnit\TestCase;

class ExtractOffsetTest extends TestCase
{
    public function testExtractOffset(): void
    {
        list($source, $offset) = ExtractOffset::fromSource('<?php class Foobar { <> }');
        $this->assertEquals(21, $offset);
        $this->assertEquals('<?php class Foobar {  }', $source);
    }
}
