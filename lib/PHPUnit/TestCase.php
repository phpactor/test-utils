<?php

namespace DMS\PHPUnitExtensions\ArraySubset{
    if (!trait_exists(ArraySubsetAsserts::class)) {
        trait ArraySubsetAsserts
        {
        }
    }
}

namespace Prophecy\PhpUnit {
    if (!trait_exists(ProphecyTrait::class)) {
        trait ProphecyTrait
        {
        }
    }
}

namespace Phpactor\TestUtils\PHPUnit {

    use DMS\PHPUnitExtensions\ArraySubset\ArraySubsetAsserts;
    use PHPUnit\Framework\TestCase as PhpUnitTestCase;
    use Prophecy\PhpUnit\ProphecyTrait;

    class TestCase extends PhpUnitTestCase
    {
        use ArraySubsetAsserts;
        use ProphecyTrait;
    }
}
