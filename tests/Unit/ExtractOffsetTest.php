<?php

namespace Phpactor\TestUtils\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Phpactor\TestUtils\ExtractOffset;
use InvalidArgumentException;

class ExtractOffsetTest extends TestCase
{
    public function testExtractOffset()
    {
        list($source, $offset) = ExtractOffset::fromSource('<?php class Foobar { <> }');
        $this->assertEquals(21, $offset);
        $this->assertEquals('<?php class Foobar {  }', $source);
    }

    public function testExtractOffsetNoOffset()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Could not find offset');

        ExtractOffset::fromSource('<?php class Foobar { }');
    }
}
