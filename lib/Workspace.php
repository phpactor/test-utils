<?php

namespace Phpactor\TestUtils;

use RecursiveIteratorIterator;
use RecursiveDirectoryIterator;
use InvalidArgumentException;

class Workspace
{
    /**
     * @var string
     */
    private $path;

    public function __construct(string $path)
    {
        $this->path = $path;
    }

    public static function create(string $path): self
    {
        return new self($path);
    }

    public function exists(string $path): bool
    {
        return file_exists($this->path($path));
    }

    public function path(string $path): string
    {
        return $this->path . DIRECTORY_SEPARATOR . $path;
    }

    public function getContents(string $path): string
    {
        if (false === $this->exists($path)) {
            throw new InvalidArgumentException(sprintf(
                'File "%s" does not exist',
                $path
            ));
        }

        return file_get_contents($this->path($path));
    }

    public function reset()
    {
        if (file_exists($this->path)) {
            $this->remove($this->path);
        }

        mkdir($this->path);
    }

    public function loadManifest(string $manifest)
    {
        foreach ($this->parseManifest($manifest) as $path => $contents) {
            $path = $this->path . DIRECTORY_SEPARATOR . $path;

            if (!file_exists(dirname($path))) {
                mkdir(dirname($path), 0777, true);
            }

            file_put_contents($path, $contents);
        }
    }

    private function parseManifest(string $manifest)
    {
        $lines = explode(PHP_EOL, $manifest);

        $buffer = [];
        $currentFile = null;

        foreach ($lines as $line) {
            $match = preg_match('{//\s?File:\s?(.*)}', $line, $matches);

            if ($match) {
                $currentFile = $matches[1];
                continue;
            }

            if (null === $currentFile) {
                continue;
            }

            if (!isset($buffer[$currentFile])) {
                $buffer[$currentFile] = [];
            }

            $buffer[$currentFile][] = $line;
        }

        return array_map(function (array $lines) {
            return implode(PHP_EOL, $lines);
        }, $buffer);
    }

    private function remove($path = '')
    {
        if (is_link($path) || is_file($path)) {
            unlink($path);
            return;
        }

        $files = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($path, RecursiveDirectoryIterator::SKIP_DOTS),
            RecursiveIteratorIterator::CHILD_FIRST
        );

        foreach ($files as $file) {
            $this->remove($file->getPathName());
        }

        rmdir($path);
    }
}
