<?php

namespace TaskTests;


use CommonTestClass;
use kalanis\kw_clipr\Output;
use kalanis\kw_clipr\Tasks\DummyTask;


class TaskTest extends CommonTestClass
{
    public function testSimple()
    {
        $inputs = $this->getParams();
        $instance = new XDummy(new Output\Web(), $inputs);
        $this->assertEquals('Just dummy task for processing info from params', $instance->desc());
        $instance->process();
        $this->assertInstanceOf('\kalanis\kw_clipr\Output\AOutput', $instance->transl());
        $this->assertNotEmpty($instance->par());
        $this->assertFalse(isset($instance->abc));
        $this->assertTrue(isset($instance->verbose));
        $this->assertTrue($instance->verbose);
    }

    protected function getParams(): array
    {
        return [
            'abc' => $this->initEntry('abc', 'abc', 'trewq'),
            'v' => $this->initEntry('verbose', 'v', true),
        ];
    }
}


class XDummy extends DummyTask
{
    public function transl()
    {
        return $this->getTranslator();
    }

    public function par()
    {
        return $this->getParams();
    }
}
