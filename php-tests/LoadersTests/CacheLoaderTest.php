<?php

namespace LoadersTests;


use kalanis\kw_clipr\CliprException;
use kalanis\kw_clipr\Loaders\CacheLoader;
use kalanis\kw_clipr\Tasks\ATask;


class CacheLoaderTest extends ALoaderTest
{
    /**
     * @throws CliprException
     */
    public function testBasic(): void
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
}
