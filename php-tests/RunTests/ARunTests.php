<?php

namespace RunTests;


use CommonTestClass;
use kalanis\kw_clipr\Interfaces\ILoader;
use kalanis\kw_clipr\Interfaces\ITargetDirs;
use kalanis\kw_clipr\Tasks\ATask;


abstract class ARunTests extends CommonTestClass
{
}


class XTask extends ATask
{
    public function process(): int
    {
        return static::STATUS_SIGNAL_DUMP;
    }

    public function desc(): string
    {
        return 'testing task';
    }
}


class XLoader implements ILoader
{
    public function getTask(string $classFromParam): ?ATask
    {
        return 'test' == $classFromParam ? new XTask() : null ;
    }
}


class XFLoader extends XLoader implements ITargetDirs
{
    /** @var array<string, array<string>> */
    protected array $paths = [];

    /**
     * @param array<string, array<string>> $paths
     */
    public function __construct(array $paths)
    {
        $this->paths = $paths;
    }

    public function getPaths(): array
    {
        return $this->paths;
    }
}
