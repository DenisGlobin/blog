@extends('layouts.app')

@section('content')

    <div class="col-md-8 blog-main">
        <h3 class="pb-3 mb-4 font-italic border-bottom">Edit profile</h3>

        <div class="card flex-md-row mb-4 box-shadow">
            <div class="card-body d-flex flex-column">
                <p>To confirm the change of your email address please check your email for a verification link</p>
                <a href="{{ route('profile') }}">Return to my profile</a>
            </div>
        </div>
    </div>

@endsection
