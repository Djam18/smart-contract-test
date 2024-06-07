@extends('layouts.dashboard')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Wallet Management') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    @if (!auth()->user()->wallet)
                        <div>
                            <form method="POST" action="{{ route('create-wallet') }}">
                                @csrf
                                <button type="submit" class="btn btn-primary">Create Wallet</button>
                            </form>
                        </div>
                    @else
                        <div>
                            <span>Address : </span> <span>{{ auth()->user()->wallet->address }}</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
