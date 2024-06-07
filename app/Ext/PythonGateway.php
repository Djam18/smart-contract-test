<?php

declare(strict_types=1);

namespace App\Ext;

class PythonGateway extends Gateway
{
    protected $runner = '';

    protected function checkRunner()
    {
        if ($this->version == 3) {
            $this->runner = config('app.python3');
        } else {
            $this->runner = config('app.python');
        }
        $result = parent::checkRunner();

        if ($result) {
            $checkCommand = $this->getRunner() . ' --version 2>&1';
            $output = [];
            exec($checkCommand, $output);
            $result = preg_match('/^Python./', $output[0]);
        }

        return $result;
    }
}
