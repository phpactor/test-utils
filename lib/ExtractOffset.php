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
        list($source, $offsetStart) = self::extractOffset($source, $marker);
        list($source, $offsetEnd) = self::extractOffset($source, $marker);

        return [$source, $offsetStart, $offsetEnd];
    }

    private static function extractOffset($source, $marker)
    {
        $offset = $offset = strpos($source, $marker);

        if (!$offset) {
            return [ $source, null ];
        }

        $source = substr($source, 0, $offset) . substr($source, $offset + 2);

        return [$source, $offset];
    }
}


