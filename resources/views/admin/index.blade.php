@extends('layouts.app')

@section('content')

    <div class="col-md-8 blog-main">
        <h3 class="pb-3 mb-4 font-italic border-bottom">Users list</h3>

        <div class="card flex-md-row mb-4 box-shadow">
            <div class="card-body d-flex flex-column align-items-start">
                @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                @endif

                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th scope="col"></th>
                        <th scope="col">User ID</th>
                        <th scope="col">User name</th>
                        <th scope="col">Registred at</th>
                    </tr>
                    </thead>
                    <tbody>

                    @foreach ($users as $user)
                        @if ($user->is_admin == false)
                            <tr class="table-default">
                                <th scope="row">
                                    <input type="checkbox" name="chkbox[]" value="{!! $user->id !!}">
                                </th>
                                <td>{{ $user->id }}</td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->created_at }}</td>
                            </tr>
                        @endif
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection