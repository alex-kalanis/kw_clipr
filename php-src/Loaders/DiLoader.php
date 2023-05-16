<?php

namespace kalanis\kw_clipr\Loaders;


use kalanis\kw_clipr\Clipr\Paths;
use kalanis\kw_clipr\Clipr\Useful;
use kalanis\kw_clipr\CliprException;
use kalanis\kw_clipr\Interfaces;
use kalanis\kw_clipr\Tasks\ATask;
use Psr\Container;
use ReflectionException;


/**
 * Class DiLoader
 * @package kalanis\kw_clipr\Tasks
 * Factory for creating tasks/commands from obtained name
 * It contains dependency injection - so everything loaded is from source targeted by that DI
 * @codeCoverageIgnore because of that internal autoloader
 */
class DiLoader implements Interfaces\ILoader
{
    /** @var Container\ContainerInterface */
    protected $container = null;

    public function __construct(Container\ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @param string $classFromParam
     * @throws CliprException
     * @throws Container\ContainerExceptionInterface
     * @throws Container\NotFoundExceptionInterface
     * @throws ReflectionException
     * @return ATask|null
     */
    public function getTask(string $classFromParam): ?ATask
    {
        $classPath = Useful::sanitizeClass($classFromParam);
        $paths = Paths::getInstance()->getPaths();
        foreach ($paths as $namespace => $path) {
            if ($this->containsPath($classPath, $namespace) && $this->container->has($classPath)) {
                $task = $this->container->get($classPath);
                $reflection = new \ReflectionClass($classPath);
                if (!$reflection->isInstantiable()) {
                    // cannot initialize the class - abstract one, interface, trait, ...
                    return null;
                }
                if (!$task instanceof ATask) {
                    // the class inside is not an instance of ATask necessary to run
                    throw new CliprException(sprintf('Class *%s* is not instance of ATask - check interface or query', $classPath), Interfaces\IStatuses::STATUS_LIB_ERROR);
                }
                return $task;
            }
        }
        return null;
    }

    protected function containsPath(string $classPath, string $namespace): bool
    {
        return (0 === mb_strpos($classPath, $namespace));
    }
}
