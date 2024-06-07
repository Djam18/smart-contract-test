<?php

// app/Http/Controllers/WalletController.php

namespace App\Http\Controllers;

use App\Ext\Gateway;
use App\Models\User;
use App\Models\Wallet;
use App\Ext\NodeGateway;
use Illuminate\Http\Request;
use Symfony\Component\Process\Process;
use Illuminate\Support\Facades\Artisan;
use App\Exceptions\Ext\GatewayException;
use Symfony\Component\Process\Exception\ProcessFailedException;

class WalletController extends Controller
{
    private Gateway $gateway;

    public function __construct(
    ) {
        $this->gateway = new NodeGateway('2');
    }

    public function index(Request $request)
    {
        $user = $request->user();

        return view('wallet.index');
    }

    public function createWallet(Request $request)
    {
        $user = $request->user();

        if ($user->wallet) {
            return redirect()->route('wallet.index')->with('error', 'Wallet already exists.');
        }
        $keyPair = [];
        $output;
        // Generate key pair using Node.js
        try {
            $output = $this->gateway->run('generateKeyPair.js', []);
        } catch (GatewayException $e) {
            throw new \Exception(_('Image could not be rendered: ' . $e->getMessage()));
        }

        $keyPair = (array)json_decode(array_values($output)[0]);

        dd($output);
        Wallet::create([
            'user_id' => $user->id,
            'address' => $keyPair['address'],
            'private_key' => $keyPair['privateKey'],
        ]);

        $balance = $this->getBalance($user);
        return redirect()->route('wallet.index')->with('status', 'Wallet created successfully.');
    }

    public function transferUsdt(Request $request)
    {
        $user = $request->user();
        $wallet = $user->wallet;

        if (!$wallet) {
            return redirect()->route('wallet.index')->with('error', 'Wallet not found.');
        }

        $toAddress = $request->input('to_address');
        $amount = $request->input('amount') * 1000000; // Convert USDT to smallest unit
        $contractAddress = env('USDT_CONTRACT_ADDRESS');
        $contractAbi = env('USDT_ABI');

        $process = new Process([
            'node',
            base_path('resources/js/transferUsdt.js'),
            $wallet->address,
            $wallet->private_key,
            $toAddress,
            $amount,
            $contractAddress,
            $contractAbi
        ]);

        $process->run();

        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }

        $output = json_decode($process->getOutput(), true);

        if (isset($output['error'])) {
            return redirect()->route('wallet.index')->with('error', $output['error']);
        }

        return redirect()->route('wallet.index')->with('status', 'Transaction successful. TX Hash: ' . $output['transactionHash']);
    }

    /**
     *
     * @param User $user
     * @return void
     */
    public function getBalance()
    {
        $user = auth()->user();
        $wallet = $user->wallet;
        if (!$wallet) {
            return null;
        }

        $output=[];
        try {
            $output = $this->gateway->run('getBalance.js', [
                $user->wallet->address,
                env('DRPC_PROVIDER')
            ]);
        } catch (GatewayException $e) {
            throw new \Exception(_('Image could not be rendered: ' . $e->getMessage()));
        }

        $balance = json_decode($output[0], true);

        if (isset($balance['error'])) {
            return null;
        }

        // Convert balance from smallest unit to USDT
        return view('wallet.balance', ['balance' => $balance]);
    }

    public function getLastBlock()
    {
        try {
            $output = $this->gateway->run('getLastblock.js', []);
            dd($output);
        } catch (GatewayException $e) {
            throw new \Exception(_('Image could not be rendered: ' . $e->getMessage()));
        }
    }


}
