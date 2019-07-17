@extends('layouts.app')

@section('content')

    <div class="col-md-8 blog-main">
        <h3 class="pb-3 mb-4 font-italic border-bottom">My profile</h3>

        <div class="card flex-md-row mb-4 box-shadow">
            <div class="card-body d-flex flex-column align-items-start">
                @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                @endif

                You are logged in as {{ $user->name }}!
            </div>
        </div>
    </div>

@endsection
