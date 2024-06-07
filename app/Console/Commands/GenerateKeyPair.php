<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Process\Process;

class GenerateKeyPair extends Command
{
    protected $signature = 'generate:keypair';
    protected $description = 'Generate a key pair using elliptic';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $process = new Process(['node', base_path('resources/js/blockchain.js')]);
        $process->run();

        if (!$process->isSuccessful()) {
            $this->error($process->getErrorOutput());
            return 1;
        }

        $output = $process->getOutput();
        $keyPair = json_decode($output, true);

        $this->info('Key Pair generated successfully');
        $this->info('Private Key: ' . $keyPair['privateKey']);
        $this->info('Public Key: ' . $keyPair['publicKey']);
        $this->info('Address: ' . $keyPair['address']);

        return 0;
    }
}
