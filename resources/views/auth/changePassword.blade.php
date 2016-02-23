@extends('app')
@section('title')
    Change Password
@endsection
@section('content')
    <form method="POST" action="/auth/change-password" class="form-horizontal">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">







        <div class="form-group">
            <label class="control-label col-sm-2" for="oldPassword">Actual Password:</label>
            <div class="col-sm-4">
                <input type="password" class="form-control" name='oldPassword' id="password" placeholder="Old password">
            </div>
        </div>

        <div class="form-group">
            <label class="control-label col-sm-2" for="newPassword">New Password:</label>
            <div class="col-sm-4">
                <input type="password" class="form-control" name='newPassword' id="password" placeholder="New password">
            </div>
        </div>

        <div class="form-group">
            <label class="control-label col-sm-2" for="confirm">Confirm:</label>
            <div class="col-sm-4">
                <input type="password" class="form-control" name='confirm' id="confirm" placeholder="Confirm password">
            </div>
        </div>

        <div class="col-sm-2">
            <input type="submit" class="form-control btn btn-success" value="Change">
        </div>

    </form>
@endsection
