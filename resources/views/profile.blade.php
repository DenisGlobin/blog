@extends('layouts.app')

@section('content')

    <div class="col-md-8 blog-main">
        <h3 class="pb-3 mb-4 font-italic border-bottom">@lang('profile.profile_user')</h3>

        <div class="card flex-md-row mb-4 box-shadow">
            <div class="card-body d-flex flex-column">

                <table class="table">
                    <tbody class="thead-light">
                    <tr>
                        <th scope="row">@lang('profile.name')</th>
                        <td>{{ $user->name }}</td>
                    </tr>
                    <tr>
                        <th scope="row">@lang('profile.first_name')</th>
                        <td>{{ $user->first_name }}</td>
                    </tr>
                    <tr>
                        <th scope="row">@lang('profile.last_name')</th>
                        <td>{{ $user->last_name }}</td>
                    </tr>
                    <tr>
                        <th scope="row">@lang('profile.email')</th>
                        <td>{{ $user->email }}</td>
                    </tr>
                    <tr>
                        <th scope="row">@lang('profile.articles_user')</th>
                        <td><a href="{{ route('user.articles', ['id' => $user->id]) }}">Link</a> </td>
                    </tr>
                    <tr>
                        <th scope="row">@lang('profile.reg_at')</th>
                        <td>{{ $user->email_verified_at }}</td>
                    </tr>
                    </tbody>
                </table>

            </div>
        </div>
    </div>

@endsection
