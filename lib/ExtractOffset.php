<?php

namespace Phpactor\TestUtils;

/**
 * Extract the offset marked by `<>` and return the source code (without the
 * symbol) numeric offset.
 */
class ExtractOffset
{
    public static function fromSource($source, $marker = '<>')
    {
        $offset = $offset = strpos($source, $marker);

        if (!$offset) {
            throw new \InvalidArgumentException(sprintf(
                'Could not find offset <> in example code: %s',
                $source
            ));
        }

        $source = substr($source, 0, $offset) . substr($source, $offset + 2);

        return [$source, $offset];
    }
}


