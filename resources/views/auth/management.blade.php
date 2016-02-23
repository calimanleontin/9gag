@extends('app')
@section('title')
    Manage Users
@endsection
@section('content')

    <form method="post" action="/auth/management" class="form">

        <input type="hidden" name='_token' value="{{csrf_token()}}">

        <div class="form-group col-md-4">
                <label for="user">Users:</label>
               <select name="user" class="form-control">

                   <option class="form-control" >Select user</option>

                   @foreach($users as $user)
                           <option class="form-control" value={{$user->name}}>{{$user->name}}</option>
                   @endforeach
                </select>
        </div>
        <div class="form-group col-md-4">
            <label for="role">Roles</label>
            <select name="role" class="form-control">

                <option class="form-control" >Select role</option>

                @foreach($roles as $role)
                    <option class="form-control" value={{$role}}>{{$role}}</option>
                @endforeach

            </select>
        </div>

        <div class="form-group col-md-3">
            <input type="submit" value="Change" class="form-control submit-management-down">
        </div>
    </form>
@endsection
