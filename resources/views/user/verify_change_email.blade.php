@extends('layouts.app')

@section('content')

    <div class="col-md-8 blog-main">
        <h3 class="pb-3 mb-4 font-italic border-bottom">@lang('profile.profile_edit')</h3>

        <div class="card flex-md-row mb-4 box-shadow">
            <div class="card-body d-flex flex-column">
                <p>@lang('profile.check_email')</p>
                <a href="{{ route('profile') }}">@lang('profile.return')</a>
            </div>
        </div>
    </div>

@endsection
