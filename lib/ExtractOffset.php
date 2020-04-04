<?php

namespace Phpactor\TestUtils;

/**
 * Extract the offset marked by `<>` and return the source code (without the
 * symbol) numeric offset.
 */
class ExtractOffset
{
    /**
     * @return array<string>
     */
    public static function fromSource(string $source, string $marker = '<>'): array
    {
        list($source, $offsetStart) = self::extractOffset($source, $marker);
        list($source, $offsetEnd) = self::extractOffset($source, $marker);

        return [$source, $offsetStart, $offsetEnd];
    }

    /**
     * Extract the byte offset from the given marked source
     * and remove the <> mark.
     *
     * @return array<mixed>
     */
    private static function extractOffset(string $source, string $marker): array
    {
        $offset = mb_strpos($source, $marker);

        if (!$offset) {
            return [ $source, null ];
        }

        $source = mb_substr($source, 0, $offset) . mb_substr($source, $offset + 2);

        return [$source, strlen(mb_substr($source, 0, $offset))];
    }
}
