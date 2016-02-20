@extends('app')
@section('title')
    New Post
@endsection
@section('content')
{!! Form::open(array('url'=>'/post/store', 'method'=>'POST', 'files'=>true)) !!}
{!! Form::token() !!}

<div class="form-group">
    {!! Form::label('title','Title') !!}
    {!! Form::text('title','',['class'=>'form-control']) !!}
</div>

<div class="form-group">
    {!! Form::label('Product Image') !!}
    {!! Form::file('image') !!}
</div>

<div class="form-group">
    {!!  Form::submit('Create',['class'=> 'btn btn-success'])!!}
</div>

{!! Form::close() !!}
@endsection
