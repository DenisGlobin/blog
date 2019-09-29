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

                <form>
                    @csrf
                    <table class="table table-hover">
                    <thead>
                    <tr>
                        <th scope="col"></th>
                        <th scope="col">User ID</th>
                        <th scope="col">Login</th>
                        <th scope="col">User email</th>
                        <th scope="col">User statistic</th>
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
                                <td><a href="{{ route('user.info', ['id' => $user->id]) }}">{{ $user->name }}</a></td>
                                <td>{{ $user->email }}</td>
                                <td><a href="{{ route('statistic.user', ['id' => $user->id]) }}">Link</a></td>
                            </tr>
                        @endif
                    @endforeach
                    </tbody>
                    </table>

                    <div class="input-group">
                        <select class="custom-select" id="banSelector">
                            <option selected>Choose...</option>
                            <option value="1">Remove the ban</option>
                            <option value="1">One day</option>
                            <option value="2">One week</option>
                            <option value="3">One month</option>
                        </select>
                        <div class="input-group-append">
                            <button class="btn btn-outline-secondary" type="submit">Submit</button>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>

@endsection
