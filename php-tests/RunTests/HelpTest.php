<?php

namespace RunTests;


use clipr\Help;
use kalanis\kw_clipr\Clipr\DummyEntry;
use kalanis\kw_clipr\Output\Clear;
use kalanis\kw_input\Filtered\EntryArrays;


class HelpTest extends ARunTests
{
    public function testBasic(): void
    {
        // init
        $lib = new Help();
        $lib->initTask(new Clear(), new EntryArrays([
            DummyEntry::init('q', ''),
            DummyEntry::init('param_2', 'test'),
        ]), new XLoader());
        $this->assertNotNull($lib);
        $this->assertEquals('Help with Clipr tasks', $lib->desc());
        // process
        $this->assertEquals(XTask::STATUS_SUCCESS, $lib->process());
    }

    public function testNoLoader(): void
    {
        // init
        $lib = new Help();
        $lib->initTask(new Clear(), new EntryArrays([
            DummyEntry::init('quiet', '1'), // full variant
        ]), null);
        $this->assertNotNull($lib);
        // process
        $this->assertEquals(XTask::STATUS_LIB_ERROR, $lib->process());
    }

    public function testNoTask(): void
    {
        // init
        $lib = new Help();
        $lib->initTask(new Clear(), new EntryArrays([
            DummyEntry::init('q', ''), // short variant
        ]), new XLoader());
        $this->assertNotNull($lib);
        // process
        $this->assertEquals(XTask::STATUS_NO_TARGET_RESOURCE, $lib->process());
    }
}
