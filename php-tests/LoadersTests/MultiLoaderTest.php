<?php

namespace LoadersTests;


use kalanis\kw_clipr\CliprException;
use kalanis\kw_clipr\Loaders\MultiLoader;
use kalanis\kw_clipr\Tasks\ATask;


class MultiLoaderTest extends ALoaderTest
{
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
