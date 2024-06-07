<?php

declare(strict_types=1);

namespace App\Ext;

use App\Exceptions\Ext\GatewayException;

abstract class Gateway
{
    protected $runner = null;
    protected $dir = null;
    protected $version = 2;
    protected $runnerOptions = [];

    public function __construct(string $version, string $dir = '')
    {
        $this->dir = base_path('app/DataLion/Ext/Python/' . $dir);
        $this->version = $version;
    }

    protected function createArguments($args)
    {
        $commandArgs = '';

        foreach ($args as $key => $value) {
            if (!empty($value)) {
                if (is_string($key)) {
                    $commandArgs .= ' --' . $key . ' "' . $value . '"';
                } else {
                    $commandArgs .= " '" . $value . "'";
                }
            }
        }

        return $commandArgs;
    }

    public function run($file, array $args): array
    {
        if (!$this->check()) {
            return [];
        }

        $command = $this->getCommand($file, $args);

        $output = [];
        exec($command . ' 2>&1', $output);

        return $output;
    }

    /**
     * Check is all necessary conditions for run the gateway
     *
     * @return bool
     * @throws GatewayException
     */
    protected function check()
    {
        $result = $this->checkRunner();

        if (!$result) {
            throw new GatewayException(sprintf(
                'The runner "%s" is not valid for gateway %s!',
                $this->runner,
                get_class($this)
            ));
        }

        $result = $this->checkDirectory();

        if (!$result) {
            throw new GatewayException(sprintf('The working directory "%s" does not exist!', $this->dir));
        }

        return $result;
    }

    /**
     * Check if the runner is valid,
     * should be overridden for the specific gateway subclasses
     *
     * @return bool
     */
    protected function checkRunner()
    {
        return $this->getRunner() !== null;
    }

    /**
     * @return string Returns the runner command to run by the gateway
     */
    protected function getRunner(): string
    {
        return $this->runner;
    }

    /**
     * Check if the working directory exists
     *
     * @return bool
     */
    protected function checkDirectory()
    {
        return file_exists($this->dir);
    }

    /**
     * @param mixed $file
     * @param array $args
     * @return string
     */
    protected function getCommand($file, array $args): string
    {
        $commandArgs = $this->createArguments($args);
        $options = implode(" ", $this->runnerOptions);

        return $this->runner . " " . $options . " " . $this->dir . DIRECTORY_SEPARATOR . $file . $commandArgs;
    }
}
