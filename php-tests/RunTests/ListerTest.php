<?php

namespace RunTests;


use clipr\Lister;
use kalanis\kw_clipr\Clipr\DummyEntry;
use kalanis\kw_clipr\CliprException;
use kalanis\kw_clipr\Loaders\KwLoader;
use kalanis\kw_clipr\Loaders\MultiLoader;
use kalanis\kw_clipr\Output\Clear;
use kalanis\kw_input\Filtered\EntryArrays;


class ListerTest extends ARunTests
{
    public function testBasic(): void
    {
        // init
        $lib = new Lister();
        $lib->initTask(new Clear(), new EntryArrays([
            DummyEntry::init('q', ''),
        ]), new XLoader());
        $this->assertNotNull($lib);
        $this->assertEquals('Render list of tasks available in paths defined for lookup', $lib->desc());
        // process
        $this->assertEquals(XTask::STATUS_SUCCESS, $lib->process());
    }

    public function testNoLoader(): void
    {
        // init
        $lib = new Lister();
        $lib->initTask(new Clear(), new EntryArrays([
            DummyEntry::init('q', ''),
        ]), null);
        $this->assertNotNull($lib);
        // process
        $this->assertEquals(XTask::STATUS_LIB_ERROR, $lib->process());
    }

    /**
     * @throws CliprException
     */
    public function testSubLoader(): void
    {
        $multi = new MultiLoader();
        $multi->addLoader(new XLoader());
        $multi->addLoader(new KwLoader([
            'data' => [__DIR__, '..', 'data'],
            'clipr' => [__DIR__, '..', '..', 'run'],
            'testing' => [__DIR__, '..', 'data'],
        ]));
        // init
        $lib = new Lister();
        $lib->initTask(new Clear(), new EntryArrays([
            DummyEntry::init('q', ''),
        ]), $multi);
        $this->assertNotNull($lib);
        // process
        $this->assertEquals(XTask::STATUS_SUCCESS, $lib->process());
    }

    /**
     * @throws CliprException
     */
    public function testOkPath(): void
    {
        // init
        $lib = new Lister();
        $lib->initTask(new Clear(), new EntryArrays([
            DummyEntry::init('q', ''),
            DummyEntry::init('path', __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'data'),
        ]), new KwLoader([
            'data' => [__DIR__, '..', 'data'],
            'clipr' => [__DIR__, '..', '..', 'run'],
            'testing' => [__DIR__, '..', 'data'],
        ]));
        $this->assertNotNull($lib);
        $this->assertEquals('Render list of tasks available in paths defined for lookup', $lib->desc());
        // process
        $this->assertEquals(XTask::STATUS_SUCCESS, $lib->process());
    }

    /**
     * @throws CliprException
     */
    public function testNoPath(): void
    {
        // init
        $lib = new Lister();
        $lib->initTask(new Clear(), new EntryArrays([
            DummyEntry::init('q', ''),
            DummyEntry::init('path', __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'data'),
        ]), new KwLoader());
        $this->assertNotNull($lib);
        $this->assertEquals('Render list of tasks available in paths defined for lookup', $lib->desc());
        // process
        $this->assertEquals(XTask::STATUS_SUCCESS, $lib->process());
    }

    public function testBadWantedPath(): void
    {
        // init
        $lib = new Lister();
        $lib->initTask(new Clear(), new EntryArrays([
            DummyEntry::init('q', ''),
            DummyEntry::init('path', 'test'),
        ]), new XLoader());
        $this->assertEquals('Render list of tasks available in paths defined for lookup', $lib->desc());
        $this->assertNotNull($lib);
        // process
        $this->assertEquals(XTask::STATUS_BAD_CONFIG, $lib->process());
    }

    public function testBadDirPath(): void
    {
        // init
        $lib = new Lister();
        $lib->initTask(new Clear(), new EntryArrays([
            DummyEntry::init('q', ''),
        ]), new XFLoader([
            'this_is_not_a_dir' => [__DIR__, '..', 'data', 'no-data', '.gitkeep'],
        ]));
        $this->assertEquals('Render list of tasks available in paths defined for lookup', $lib->desc());
        $this->assertNotNull($lib);
        // process
        $this->assertEquals(XTask::STATUS_BAD_CONFIG, $lib->process());
    }

    public function testNoFiles(): void
    {
        // init
        $lib = new Lister();
        $lib->initTask(new Clear(), new EntryArrays([
            DummyEntry::init('q', ''),
            DummyEntry::init('path', __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'no-data'),
        ]), new XLoader());
        $this->assertEquals('Render list of tasks available in paths defined for lookup', $lib->desc());
        $this->assertNotNull($lib);
        // process
        $this->assertEquals(XTask::STATUS_NO_TARGET_RESOURCE, $lib->process());
    }
}
