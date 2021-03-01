<?php

namespace kalanis\kw_clipr;


use kalanis\kw_clipr\Interfaces\ISources;
use kalanis\kw_input\Inputs;


class Clipr
{
    protected $inputs = null;
    protected $sources = null;
    protected $output = null;

    public function __construct()
    {
        $this->inputs = new Inputs();
        $this->sources = new Clipr\Sources();
    }

    /**
     * @param string $namespace
     * @param string $path
     * @return $this
     * @throws CliprException
     */
    public function addPath(string $namespace, string $path): self
    {
        Clipr\Paths::getInstance()->addPath($namespace, $path);
        return $this;
    }

    /**
     * @param array $cliArgs
     * @throws CliprException
     */
    public function run(array $cliArgs = []): void
    {
        // void because echo must stay here - we have progress indicator and that needs access to output
        $this->inputs->setSource($cliArgs)->loadEntries();
        $inputAll = $this->inputs->intoKeyObjectArray($this->inputs->getIn());

        // for parsing default params it's necessary to load another task
        $dummy = new Tasks\DummyTask(new Output\Clear(), $inputAll);
        $this->sources->determineInput((bool)$dummy->webOutput, (bool)$dummy->noColor);

        // now we know necessary input data, so we can initialize real task
        $inputs = $this->inputs->intoKeyObjectArray($this->inputs->getIn(null, $this->sources->getEntryTypes()));
        $task = Tasks\TaskFactory::getInstance()->getTask($this->sources->getOutput(), $inputs);

        if (ISources::OUTPUT_STD != $task->outputFile) {
            ob_start();
        }

        if (false === $task->noHeaders) {
            $task->writeHeader();
        }

        $task->process();

        if (false === $task->noHeaders) {
            $task->writeFooter();
        }

        if (ISources::OUTPUT_STD != $task->outputFile) {
            file_put_contents($task->outputFile, ob_get_clean(), (false === $task->noAppend ? FILE_APPEND : 0));
        }
    }
}
