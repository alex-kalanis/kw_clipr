<?php

namespace TasksTests;


use CommonTestClass;
use kalanis\kw_clipr\CliprException;
use kalanis\kw_clipr\Output;
use kalanis\kw_clipr\Tasks\DummyTask;
use kalanis\kw_clipr\Loaders\KwLoader;
use kalanis\kw_input\Filtered\SimpleFromArrays;


class TaskTest extends CommonTestClass
{
    /**
     * @throws CliprException
     */
    public function testSimple()
    {
        $inputs = $this->getParams();
        $instance = new XDummy();
        $instance->initTask(new Output\Web(), new SimpleFromArrays($inputs), new KwLoader([
            'clipr' => [__DIR__, '..', '..', 'run'],
            'testing' => [__DIR__, '..', 'data']
        ]));
        $this->assertEquals('Just dummy task for processing info from params', $instance->desc());
        $this->assertEquals(XDummy::STATUS_SUCCESS, $instance->process());
        $this->assertInstanceOf(Output\AOutput::class, $instance->transl());
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
