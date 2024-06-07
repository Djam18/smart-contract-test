@extends('layouts.dashboard')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Wallet balance') }}</div>
                <div class="card-body">
                    <div>
                        @if (auth()->user()->wallet)
                            <p><strong>Wallet Address:</strong> {{ auth()->user()->wallet->address }}</p>
                                @csrf
                                <div class="form-group">
                                    <form action="route('transfer-usdt')" method="post">
                                        <label for="to_address">Balance</label>
                                        <input type="text" class="form-control" id="to_address" name="to_address" disabled readonly value="{{ $balance }} MATIC" >
                                        <button class="my-1 btn btn-sm btn-primary"> Transfert to USDT </button>
                                    </form>
                                </div>
                        @else
                            <form method="POST" action="{{ route('create-wallet') }}">
                                @csrf
                                <button type="submit" class="btn btn-primary">Create Wallet</button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
