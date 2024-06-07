<?php

namespace App\Ext;

class NodeGateway extends Gateway
{
    protected $runner = '';
    protected $runnerOptions = ['--unhandled-rejections=strict'];

    public function __construct(string $version, string $dir = '')
    {
        $this->dir = base_path('app/Ext/Node' . $dir);
        $this->version = $version;
    }

    protected function checkRunner()
    {
        $this->runner = config('app.node');
        return parent::checkRunner();
    }
}
