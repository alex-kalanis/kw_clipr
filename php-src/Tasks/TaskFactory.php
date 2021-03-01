<?php

namespace kalanis\kw_clipr\Tasks;


use kalanis\kw_clipr\Clipr\Paths;
use kalanis\kw_clipr\CliprException;
use kalanis\kw_clipr\Interfaces\ISources;
use kalanis\kw_clipr\Output\AOutput;
use kalanis\kw_input\Interfaces\IEntry;
use kalanis\kw_input\Parsers;


/**
 * Class TaskFactory
 * @package kalanis\kw_clipr\Tasks
 * Factory for creating tasks/commands from obtained name
 * In reality it runs like autoloader of own
 * @codeCoverageIgnore because of that internal autoloader
 */
class TaskFactory
{
    const EXT_PHP = '.php';
    protected static $instance = null;
    protected $loadedClasses = [];
    protected $paths = [];

    public static function getInstance(): self
    {
        if (empty(static::$instance)) {
            static::$instance = new static();
        }
        return static::$instance;
    }

    protected function __construct()
    {
    }

    /**
     * @codeCoverageIgnore why someone would run that?!
     */
    private function __clone()
    {
    }

    /**
     * @param AOutput $translator
     * @param array $inputs
     * @param string $defaultTask
     * @param int $paramPosition
     * @return ATask
     * @throws CliprException
     * For making instances from more than one path
     * Now it's possible to read from different paths as namespace sources
     * Also each class will be loaded only once
     */
    public function getTask(AOutput $translator, array &$inputs, string $defaultTask = 'clipr\Info', int $paramPosition = 0): ATask
    {
        $classFromParam = TaskFactory::nthParam($inputs, $paramPosition);
        $classPath = TaskFactory::sanitizeClass($classFromParam ?: $defaultTask);
        if (empty($this->loadedClasses[$classPath])) {
            $this->loadedClasses[$classPath] = $this->initTask($translator, $classPath, $inputs);
        }
        return $this->loadedClasses[$classPath];
    }

    /**
     * @param AOutput $translator
     * @param string $classPath
     * @param array $inputs
     * @return ATask
     * @throws CliprException
     */
    protected function initTask(AOutput $translator, string $classPath, array &$inputs): ATask
    {
        $paths = Paths::getInstance()->getPaths();
        foreach ($paths as $namespace => $path) {
            if ($this->containsPath($classPath, $namespace)) {
                $translatedPath = Paths::getInstance()->classToReal($classPath, $namespace);
                $realPath = $this->makeRealPath($path, $translatedPath);
                require_once $realPath;
                $class = new $classPath($translator, $inputs);
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
     * @param string $obtainedPath
     * @param string $path
     * @return string
     * @throws CliprException
     */
    protected function makeRealPath(string $path, string $obtainedPath): string
    {
        $setPath = $path . $obtainedPath . ISources::EXT_PHP;
        $realPath = realpath($setPath);
        if (empty($realPath)) {
            throw new CliprException(sprintf('There is problem with path *%s* - it does not exists!', $setPath));
        }
        return $realPath;
    }

    public static function nthParam(array $inputs, $position = 0): ?string
    {
        $nthKey = Parsers\Cli::UNSORTED_PARAM . $position;
        foreach ($inputs as $input) {
            /** @var IEntry $input */
            if ($input->getKey() == $nthKey) {
                return $input->getValue();
            }
        }
        return null;
    }

    public static function sanitizeClass(string $input): string
    {
        $input = strtr($input, [':' => '\\', '/' => '\\']);
        return ('\\' == $input[0]) ? mb_substr($input, 1) : $input ;
    }

    public static function getTaskCall(ATask $class): string
    {
        return strtr(get_class($class), '\\', '/');
    }
}
