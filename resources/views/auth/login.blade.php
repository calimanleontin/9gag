@extends('app')
@section('title')
    Login
@endsection
@section('content')
<form method="POST" action="/auth/login" class="form-horizontal">
    <input type="hidden" name="_token" value="{{ csrf_token() }}">

    <div class="form-group">
        <label class="control-label col-sm-2" for="email">Email:</label>
        <div class="col-sm-4">
            <input type="email" name="email" class="form-control" id="email" placeholder="Enter email">
        </div>
    </div>

    <div class="form-group">
        <label class="control-label col-sm-2" for="password">Password:</label>
        <div class="col-sm-4">
            <input type="password" name="password" class="form-control" id="password" placeholder="Enter password">
        </div>
    </div>

        <div class="col-sm-2">
            <input type="submit" class="form-control btn btn-success" value="Login">
        </div>

</form>
@endsection
