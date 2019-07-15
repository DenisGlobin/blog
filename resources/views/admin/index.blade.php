@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Users list</div>

                    <div class="card-body">
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
        </div>
    </div>
@endsection