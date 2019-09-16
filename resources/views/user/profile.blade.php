@extends('layouts.app')

@section('content')

    <div class="col-md-8 blog-main">
        <h3 class="pb-3 mb-4 font-italic border-bottom">My profile</h3>

        <div class="card flex-md-row mb-4 box-shadow">
            <div class="card-body d-flex flex-column">

                    <form method="POST" action="{{ route('profile.edit') }}">
                        @csrf
                        {{-- User's ID --}}
                        <input id="id" name="id" type="hidden" value="{{ isset($user->id) ? $user->id : null }}">
                        {{-- User's NickName --}}
                        <div class="form-group row">
                            <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('profile.login') }}</label>

                            <div class="col-md-6 input-group">
                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ $user->name }}" required autocomplete="name" disabled>
                                <div class="input-group-append">
                                    <button class="btn btn-outline-secondary" type="button" id="buttonName">Change</button>
                                </div>
                                @error('name')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        {{-- User's Email --}}
                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('profile.email') }}</label>

                            <div class="col-md-6 input-group">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ $user->email }}" required autocomplete="email" disabled>
                                <div class="input-group-append">
                                    <button class="btn btn-outline-secondary" type="button" id="buttonEmail">Change</button>
                                </div>
                                @error('email')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        {{-- User's First Name --}}
                        <div class="form-group row">
                            <label for="firstName" class="col-md-4 col-form-label text-md-right">First Name</label>

                            <div class="col-md-6 input-group">
                                <input id="firstName" type="text" class="form-control @error('firstName') is-invalid @enderror" name="firstName" value="{{ isset($user->first_name) ? $user->first_name : null }}" required autocomplete="firstName" disabled>
                                <div class="input-group-append">
                                    <button class="btn btn-outline-secondary" type="button" id="buttonFirstName">Change</button>
                                </div>
                                @error('firstName')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        {{-- User's Last Name --}}
                        <div class="form-group row">
                            <label for="lastName" class="col-md-4 col-form-label text-md-right">Last Name</label>

                            <div class="col-md-6 input-group">
                                <input id="lastName" type="text" class="form-control @error('lastName') is-invalid @enderror" name="firstName" value="{{ isset($user->last_name) ? $user->last_name : null }}" required autocomplete="lastName" disabled>
                                <div class="input-group-append">
                                    <button class="btn btn-outline-secondary" type="button" id="buttonLastName">Change</button>
                                </div>
                                @error('lastName')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        {{-- User's verified date --}}
                        <div class="form-group row justify-content-center">
                            <p class="blog-post-meta">{{ __('profile.reg_at') }} {!! $user->email_verified_at !!}</p>
                        </div>
                        {{-- Submit button for save changes --}}
                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('profile.save_btn') }}
                                </button>
                            </div>
                        </div>
                    </form>

            </div>
        </div>
    </div>

@endsection

@section('js')
    <script>
        // Enable Login field
        $(function () {
            $("button#buttonName").on('click', function () {
                $("input#name").removeAttr("disabled").focus();
            })
        });
        // Enable Email field
        $(function () {
            $("button#buttonEmail").on('click', function () {
                $("input#email").removeAttr("disabled").focus();
            })
        });
        // Enable First name field
        $(function () {
            $("button#buttonFirstName").on('click', function () {
                $("input#firstName").removeAttr("disabled").focus();
            })
        });
        // Enable Last name field
        $(function () {
            $("button#buttonLastName").on('click', function () {
                $("input#lastName").removeAttr("disabled").focus();
            })
        });
    </script>
@endsection