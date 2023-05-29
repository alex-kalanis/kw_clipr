<?php

namespace RunTests;


use clipr\Info;
use kalanis\kw_clipr\Clipr\DummyEntry;
use kalanis\kw_clipr\Output\Clear;
use kalanis\kw_input\Filtered\EntryArrays;


class InfoTest extends ARunTests
{
    public function testBasic(): void
    {
        // init
        $lib = new Info();
        $lib->initTask(new Clear(), new EntryArrays([
            DummyEntry::init('q', ''),
        ]), new XLoader());
        $this->assertNotNull($lib);
        $this->assertEquals('Info about Clipr and its inputs', $lib->desc());
        // process
        $this->assertEquals(XTask::STATUS_SUCCESS, $lib->process());
    }
}
