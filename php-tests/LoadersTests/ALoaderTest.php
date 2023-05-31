<?php

namespace LoadersTests;


use CommonTestClass;
use kalanis\kw_clipr\Interfaces\ILoader;
use kalanis\kw_clipr\Tasks\ATask;


abstract class ALoaderTest extends CommonTestClass
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
