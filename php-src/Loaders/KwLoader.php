<?php

namespace kalanis\kw_clipr\Loaders;


use kalanis\kw_clipr\Clipr\Paths;
use kalanis\kw_clipr\Clipr\Useful;
use kalanis\kw_clipr\CliprException;
use kalanis\kw_clipr\Interfaces\ILoader;
use kalanis\kw_clipr\Interfaces\ISources;
use kalanis\kw_clipr\Tasks\ATask;


/**
 * Class TaskFactory
 * @package kalanis\kw_clipr\Tasks
 * Factory for creating tasks/commands from obtained name
 * In reality it runs like autoloader of own
 * @codeCoverageIgnore because of that internal autoloader
 */
class KwLoader implements ILoader
{
    const EXT_PHP = '.php';

    /**
     * @param string $classFromParam
     * @return ATask
     * @throws CliprException
     * For making instances from more than one path
     * Now it's possible to read from different paths as namespace sources
     * Also each class will be loaded only once
     */
    public function getTask(string $classFromParam): ?ATask
    {
        $classPath = Useful::sanitizeClass($classFromParam);
        $paths = Paths::getInstance()->getPaths();
        foreach ($paths as $namespace => $path) {
            if ($this->containsPath($classPath, $namespace)) {
                $translatedPath = Paths::getInstance()->classToRealFile($classPath, $namespace);
                $realPath = $this->makeRealFilePath($path, $translatedPath);
                require_once $realPath;
                $class = new $classPath();
                return $class;
            }
        }
        throw new CliprException(sprintf('Unknown class *%s* - check name, interface or your config paths.', $classPath));
    }

    protected function containsPath(string $classPath, string $namespace): bool
    {
        return (0 === mb_strpos($classPath, $namespace));
    }

    /**
     * @param string $namespacePath
     * @param string $classPath
     * @return string
     * @throws CliprException
     */
    protected function makeRealFilePath(string $namespacePath, string $classPath): string
    {
        $setPath = $namespacePath . $classPath . ISources::EXT_PHP;
        $realPath = realpath($setPath);
        if (empty($realPath)) {
            throw new CliprException(sprintf('There is problem with path *%s* - it does not exists!', $setPath));
        }
        return $realPath;
    }
}