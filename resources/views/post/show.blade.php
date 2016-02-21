@extends('app')
@section('title')
    @if($post)
        {{ $post->title }}
    @else
        Page does not exist
    @endif
@endsection

@section('content')
    @if(!empty($post))

        <div>
        </div>
        <div class="list-group-item">
            <p>Created at {{ $post->created_at->format('M d,Y \a\t h:i a') }} By <a href="{{ url('/user/'.$post->user_id)}}">{{ $post->user->name }}</a>
                visited
                @if($post->views == 1)
                    one single time
                @else
                    {{$post->views}} times
                @endif
            </p>
            <article>
                <img src="../images/catalog/{{$post->image}}" alt="Smiley face" class = 'img-responsive'>
                @if(!Auth::guest())
                <a href="/gag/post/like/{{$post->id}}"><button class="btn btn-success">Up</button></a>
                <a href="/gag/post/dislike/{{$post->id}}"><button class="btn btn-warning">Down</button></a>
                    @endif

            </article>
        </div>
        <div>
            <h2>Leave a comment</h2>
        </div>
        @if(Auth::guest())
            <p>Login to Comment</p>
        @else
            <div class="panel-body">
                <form method="post" action="/comment/add">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="on_post" value="{{ $post->id }}">
                    <input type="hidden" name="slug" value="{{ $post->slug }}">
                    <div class="form-group">
                        <textarea required="required" placeholder="Enter comment here" name = "body" class="form-control"></textarea>
                    </div>
                    <input type="submit" name='post_comment' class="btn btn-success" value = "Add comment"/>
                </form>
            </div>
        @endif
        <div>
            @if(!empty($comments))
                <ul style="list-style: none; padding: 0">
                    @foreach($comments as $comment)
                        <li class="panel-body">
                            <div class="list-group">
                                <div class="list-group-item">
                                    <h3>{{ $comment->author->name }}</h3>
                                    <p>{{ $comment->created_at->format('M d,Y \a\t h:i a') }}</p>
                                </div>
                                <div class="list-group-item">
                                    <p>{{ $comment->body }}</p>
                                    @if(!Auth::guest() && ($comment->from_user == Auth::user()->id || Auth::user()->is_admin() || Auth::user()->is_moderator() ))
                                        <a href="{{  url('comment/delete/'.$comment->id) }}" class="btn btn-danger">Delete comment</a>
                                    @endif
                                </div>
                            </div>
                        </li>
                    @endforeach
                </ul>
            @endif
            @endif
        </div>
@endsection
<script src="//cdn.tinymce.com/4/tinymce.min.js"></script>
<script>tinymce.init({ selector:'textarea' });</script>