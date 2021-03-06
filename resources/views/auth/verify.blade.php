@extends('layouts.app')

@section('content')

    <div class="col-md-8 blog-main">
        <h3 class="pb-3 mb-4 font-italic border-bottom">@lang('authforms.verify_email')</h3>

        <div class="card flex-md-row mb-4 box-shadow">
            <div class="card-body d-flex flex-column">
                @if (session('resent'))
                    <div class="alert alert-success" role="alert">
                        @lang('authforms.fresh_verif_link')
                    </div>
                @endif

                @lang('authforms.verif_link')
                @lang('authforms.is_receive'), <a href="{{ route('verification.resend') }}">@lang('authforms.verif_request')</a>.
            </div>
        </div>
    </div>

@endsection
