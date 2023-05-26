<?php

namespace LoadersTests;


use CommonTestClass;
use kalanis\kw_clipr\CliprException;
use kalanis\kw_clipr\Interfaces\ILoader;
use kalanis\kw_clipr\Loaders\CacheLoader;
use kalanis\kw_clipr\Loaders\MultiLoader;
use kalanis\kw_clipr\Tasks\ATask;


class LoaderTest extends CommonTestClass
{
    /**
     * @throws CliprException
     */
    public function testCache(): void
    {
        $lib = CacheLoader::init(new XLoader());
        $instance1 = $lib->getTask('test');
        $this->assertInstanceOf(ATask::class, $instance1);
        $this->assertEquals(ATask::STATUS_SIGNAL_DUMP, $instance1->process());
        $instance2 = $lib->getTask('test');
        $this->assertTrue($instance1 === $instance2);
        $instance3 = $lib->getTask('nope');
        $this->assertNull($instance3);
        $this->assertNotEmpty($lib->getLoaders());
    }

    /**
     * @throws CliprException
     */
    public function testMulti(): void
    {
        $lib = MultiLoader::init();
        $lib->addLoader(new XLoader());
        $instance1 = $lib->getTask('test');
        $this->assertInstanceOf(ATask::class, $instance1);
        $instance3 = $lib->getTask('nope');
        $this->assertNull($instance3);
        $this->assertNotEmpty($lib->getLoaders());
    }
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
