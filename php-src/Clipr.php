<?php

namespace kalanis\kw_clipr;


use kalanis\kw_input\Interfaces\IFiltered;


/**
 * Class Clipr
 * @package kalanis\kw_clipr
 * Main class which runs the whole task system
 */
class Clipr
{
    protected Interfaces\ILoader $loader;
    protected IFiltered $variables;
    protected Clipr\Sources $sources;

    public function __construct(Interfaces\ILoader $loader, Clipr\Sources $sources, IFiltered $variables)
    {
        $this->loader = $loader;
        $this->sources = $sources;
        $this->variables = $variables;
    }

    /**
     * @throws CliprException
     * @return int
     */
    public function run(): int
    {
        // for parsing default params it's necessary to load another task
        $dummy = new Tasks\DummyTask();
        $dummy->initTask(new Output\Clear(), $this->variables, $this->loader);
        $this->sources->determineInput((bool) $dummy->webOutput, (bool) $dummy->noColor);

        // now we know necessary input data, so we can initialize real task
        $inputs = $this->variables->getInArray(null, $this->sources->getEntryTypes());
        $taskName = Clipr\Useful::getNthParam($inputs) ?? Interfaces\ILoader::DEFAULT_TASK;
        $task = $this->loader->getTask($taskName);
        if (!$task) {
            throw new CliprException(sprintf('Unknown task *%s* - check name, interface or your config paths.', $taskName), Interfaces\IStatuses::STATUS_CLI_USAGE);
        }
        $task->initTask($this->sources->getOutput(), $this->variables, $this->loader);

        if (Interfaces\ISources::OUTPUT_STD != $task->outputFile) {
            ob_start();
        }

        if (false === $task->noHeaders) {
            $task->writeHeader();
        }

        $result = $task->process();

        if (false === $task->noHeaders) {
            $task->writeFooter();
        }

        if (Interfaces\ISources::OUTPUT_STD != $task->outputFile) {
            file_put_contents($task->outputFile, ob_get_clean(), (false === $task->noAppend ? FILE_APPEND : 0));
        }

        return $result;
    }
}
