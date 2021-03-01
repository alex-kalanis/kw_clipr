<?php

namespace kalanis\kw_clipr\Tasks;


/**
 * Class ASingleTask
 * @package kalanis\kw_clipr\Tasks
 * @property bool singleInstance
 * @todo: Edit locks for Windows!!!
 */
abstract class ASingleTask extends ATask
{
    protected $lockFile = null;

    protected function startup(): void
    {
        parent::startup();
        $this->params->addParam('singleInstance', 'single-instance', null, false, 's', 'Call only single instance');

        $this->checkSingleInstance();
    }

    protected function checkSingleInstance()
    {
        if ($this->isSingleInstance() && $this->isFileLocked()) {
            // check if exists another instance
            die('One script instance is already running under pid ' . trim(file_get_contents($this->getLockFileName())));
            // create own lock file
        }
    }

    protected function isSingleInstance(): bool
    {
        return (true == $this->singleInstance);
    }

    protected function isFileLocked()
    {
        if (file_exists($this->getLockFileName())) {
            $lockingPID = trim(file_get_contents($this->getLockFileName()));
            $pids = explode(PHP_EOL, trim(`ps -e | awk '{print $1}'`));
            if (in_array($lockingPID, $pids)) {
                return true;
            }
            $this->output->writeLn("Removing stale lock file.");
            $this->unlinkLockFile();
        }

        file_put_contents($this->getLockFileName(), getmypid() . PHP_EOL);
        register_shutdown_function([$this, 'unlinkLockFile']);
        return false;

    }

    protected function getLockFileName()
    {
        return $this->tmpPath . str_replace('/', ':', get_class($this)) . '.lock';
    }

    public function unlinkLockFile()
    {
        @unlink($this->getLockFileName());
    }
}
