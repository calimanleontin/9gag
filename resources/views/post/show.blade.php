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
            <p>Created at {{ $post->created_at->format('M d,Y \a\t h:i a') }}
                </br>
                </br>
                By <a href="{{ url('/user/'.$post->user_id)}}">{{ $post->user->name }}</a>
                visited
                @if($post->views == 1)
                    one single time
                @else
                    {{$post->views}} times
                @endif
            </p>
            <article>
                <img src="../images/catalog/{{$post->image}}" style="width: 500px;" alt="Smiley face" class = 'img-responsive'>
              <br>
                @if(!Auth::guest())
                    @if(empty($rating))
                <a href="/gag/post/like/{{$post->id}}"><span class="glyphicon glyphicon-thumbs-up gray"></span></a>
                <a href="/gag/post/dislike/{{$post->id}}"><span class="glyphicon glyphicon-thumbs-down gray"></span></a>
                    @elseif($rating->likes == 1 and $rating->dislikes == 0)
                        <a href="/gag/post/like/{{$post->id}}"><span class=" glyphicon glyphicon-thumbs-up" green></span></a>
                        <a href="/gag/post/dislike/{{$post->id}}"><span class="glyphicon glyphicon-thumbs-down gray"></span></a>
                    @elseif($rating->likes == 0 and $rating->dislikes == 1)
                        <a href="/gag/post/like/{{$post->id}}"><span class="glyphicon glyphicon-thumbs-up gray"></span></a>
                        <a href="/gag/post/dislike/{{$post->id}}"><span class="glyphicon glyphicon-thumbs-down red"></span></a>
                    @else

                        <a href="/gag/post/like/{{$post->id}}"><span class="glyphicon glyphicon-thumbs-up gray"></span></a>
                        <a href="/gag/post/dislike/{{$post->id}}"><span class="glyphicon glyphicon-thumbs-down gray"></span></a>
                    @endif
                    @endif
                No votes: {{$votes}}

            </article>
        </div>
        <div>
            <h2>Leave a comment</h2>
        </div>
        @if(Auth::guest())
            <p>Login to Comment</p>
        @else
            <div class="panel-body">
                <form method="post" action="/gag/comment/store">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="post_id" value="{{ $post->id }}">
                    <input type="hidden" name="slug" value="{{ $post->slug }}">
                    <div class="form-group">
                        <textarea required="required" placeholder="Enter comment here" name = "content" class="form-control">
                        </textarea>
                    </div>
                    <input type="submit" name='post_comment' class="btn btn-default" value = "Add comment"/>
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
                                    By<h4>{{ $comment->user->name }}</h4>
                                    <p>{{ $comment->created_at->format('M d,Y \a\t h:i a') }}</p>
                                    <a href="{{  url('comment/delete/'.$comment->id) }}" class="btn btn-danger">Delete</a>

                                </div>
                                <div class="list-group-item">
                                    <p>{!! $comment->content !!}</p>

                                </div>
                                @if(!Auth::guest() && ($comment->user_id == Auth::user()->id || Auth::user()->is_admin()))
                                    <div class="list-group-item">
                                        @if(!Auth::guest())
                                            @if(empty($commentrating))
                                                <a href="/gag/comment/like/{{$comment->id}}"><span class="glyphicon glyphicon-thumbs-up gray"></span></a>
                                                <a href="/gag/comment/dislike/{{$comment->id}}"><span class="glyphicon glyphicon-thumbs-down gray"></span></a>
                                            @elseif($commentrating->likes == 1 and $commentrating->dislikes == 0)
                                                <a href="/gag/comment/like/{{$comment->id}}"><span class=" glyphicon glyphicon-thumbs-up" green></span></a>
                                                <a href="/gag/comment/dislike/{{$comment->id}}"><span class="glyphicon glyphicon-thumbs-down gray"></span></a>
                                            @elseif($commentrating->likes == 0 and $commentrating->dislikes == 1)
                                                <a href="/gag/comment/like/{{$comment->id}}"><span class="glyphicon glyphicon-thumbs-up gray"></span></a>
                                                <a href="/gag/comment/dislike/{{$comment->id}}"><span class="glyphicon glyphicon-thumbs-down red"></span></a>
                                            @else

                                                <a href="/gag/comment/like/{{$comment->id}}"><span class="glyphicon glyphicon-thumbs-up gray"></span></a>
                                                <a href="/gag/comment/dislike/{{$comment->id}}"><span class="glyphicon glyphicon-thumbs-down gray"></span></a>
                                            @endif
                                        @endif
                                        No votes: {{$votes}}<p>
                                        {!! Form::open(array('url'=>'/response/store', 'method'=>'POST', 'files'=>true, 'id' => 'id'.$comment->id)) !!}

                                        {!! Form::token() !!}

                                        {!! Form::hidden('comment_id', $comment->id) !!}

                                        {!! Form::hidden('post_slug', $post->slug) !!}

                                        <div class="form-group">
                                                {!! Form::label('content','Reply') !!}
                                                {!! Form::textarea('content','',['class'=>'form-control']) !!}
                                            </div>

                                        <div class="form-group">
                                            {!!  Form::submit('Replay',['class'=> 'btn btn-default'])!!}
                                        </div>


                                        {!! Form::close() !!}
                                        </p>

                                        <p class="response-btn" id="show{{$comment->id}}" onclick='showForm({{$comment->id}})'>
                                            Replay
                                        </p>
                                        </p>
                                    </div>
                                @endif

                            </div>
                        </li>
                    @endforeach
                </ul>
            @else
                There are no comments. Please say something nice.
            @endif
            @endif
        </div>
@endsection
<script src="//cdn.tinymce.com/4/tinymce.min.js"></script>
<script>tinymce.init({ selector:'textarea' });</script>