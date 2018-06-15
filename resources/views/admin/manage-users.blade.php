@extends('layouts.main-layout')

@section('title', 'home')

@section('content-header')
    <h1>Manage Users</h1>
@stop

@section('content')
    <div class="box box-info">
        <div class="box-header with-border">
            <h3 class="box-title">Edit roles for users</h3>
        </div>
        <div class="box-body">
            <table id="userListTable" class="table table-hover">
                <tbody>
                <tr>
                    <th>
                        Name
                    </th>
                    <th>
                        Surname
                    </th>
                    <th>
                        Email
                    </th>
                    <th>
                        Roles
                    </th>
                    <th>
                        Action
                    </th>
                </tr>
                @foreach ($viewModel->users as $user)
                        <tr>
                            <td>
                                {{ $user->name }}
                            </td>
                            <td>
                                {{ $user->surname }}
                            </td>
                            <td>
                                {{ $user->email }}
                            </td>
                            <td>
                                @foreach($user->roles as $role)
                                    {{ $role->name }}@if(!$loop->last), @endif
                                @endforeach
                            </td>
                            <td>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <form action="{{ url('admin/edit-user') . '/' . $user->id }}" method="GET">
                                            <button type="submit" class="btn btn-block btn-primary">Edit user</button>
                                        </form>
                                    </div>
                                    <div class="col-sm-6">
                                        @if ($user->id != Auth::id())
                                            @if(!$user->trashed())
                                                <form class="form-disable" action="{{ url('user/delete') }}" method="POST">
                                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                    <input type="hidden" name="id" value="{{ $user->id }}">
                                                    <button type="submit" class="btn btn-block btn-danger">Deactivate user</button>
                                                </form>
                                            @else
                                                <form class="form-disable" action="{{ url('user/restore') }}" method="POST">
                                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                    <input type="hidden" name="id" value="{{ $user->id }}">
                                                    <button type="submit" class="btn btn-block btn-success">Reactivate user</button>
                                                </form>
                                            @endif
                                        @endif
                                    </div>
                                </div>
                            </td>
                        </tr>
                @endforeach
                </tbody>
            </table>
            {{ $viewModel->users->links() }}
        </div>
    </div>

    <div class="box box-info">
        <div class="box-header with-border">
            <h3 class="box-title">Add new user</h3>
        </div>
        <div class="box-body">
            <form action="{{ url('admin/add-user') }}" method="POST">
                {{ csrf_field() }}
                <div class="row">
                    <div class="col-md-6">

                        <div class="col-md-12 form-group">
                            <em>Insert email and choose role. If the email exists in database, the role will be added to the user.</em>
                            <br>
                            <br>
                            <input id="email" type="email" class="form-control" name="email" required autofocus
                                   placeholder="Email">
                        </div>
                        <div class="col-md-12">
                            <div class="col-md-6 no-padding form-group">

                                <select class="form-control" name="roleselect">
                                    @foreach ($viewModel->allRoles as $role)
                                        <option value="{{ $role->id }}" name="roleVal[{{ $role->id }}]">
                                            {{ $role->name }}
                                        </option>
                                    @endforeach
                                </select>

                            </div>
                        </div>
                        <div class="col-md-12 margin-top">
                            <em>
                                Data for new user (if the email does not exist in the database, a new user will be created):
                            </em>
                            <div class="form-group margin-top">
                                <input id="name" type="text" class="form-control" name="name" required autofocus
                                       placeholder="Name">
                            </div>
                            <div class="form-group">
                                <input id="surname" type="text" class="form-control" name="surname" required autofocus
                                       placeholder="Surname">
                            </div>
                            <div class="form-group">
                                <input id="password" type="password" class="form-control" name="password" required autofocus
                                       placeholder="Password">
                            </div>

                            <div class="col-md-3 no-padding form-group">
                                <button type="submit" class="btn btn-primary btn-block ">Add user</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@stop