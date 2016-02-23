@extends('app')
@section('title')
    @if($post)
        {{ $post->title }}
    @else
        Page does not exist
    @endif
@endsection

@section('content')
    <script src="//cdn.ckeditor.com/4.5.7/standard/ckeditor.js"></script>
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
                No votes:
                @if($post->votes < 0)
                    0
                @else
                    {{$post->votes}}
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
                <form method="post" action="/gag/comment/store">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="post_id" value="{{ $post->id }}">
                    <input type="hidden" name="slug" value="{{ $post->slug }}">
                    <div class="form-group">
                        <textarea required="required" placeholder="Enter comment here" name = "content" class="form-control">
                        </textarea>
                        <script>
                            CKEDITOR.replace( 'content' );
                        </script>
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

                                    <div class="list-group-item">
                                        <ul class="list-inline">

                                            <li>
                                        <p class="response-btn" id="show{{$comment->id}}" onclick='showForm({{$comment->id}})'>
                                            Replay
                                        </p>
                                            </li>
                                            <li>
                                        @if(!Auth::guest())
                                            <?php $commentrating = $comment->getRating($comment->id, Auth::user()->id);  ?>
                                            @if($commentrating == null)
                                                <a href="/gag/comment/like/{{$post->id}}/{{$comment->id}}"><span class="glyphicon glyphicon-thumbs-up gray"></span></a>
                                                <a href="/gag/comment/dislike/{{$post->id}}/{{$comment->id}}"><span class="glyphicon glyphicon-thumbs-down gray"></span></a>
                                            @elseif($commentrating->likes == 1 and $commentrating->dislikes == 0)
                                                <a href="/gag/comment/like/{{$post->id}}/{{$comment->id}}"><span class=" glyphicon glyphicon-thumbs-up green" ></span></a>
                                                <a href="/gag/comment/dislike/{{$post->id}}/{{$comment->id}}"><span class="glyphicon glyphicon-thumbs-down gray"></span></a>
                                            @elseif($commentrating->likes == 0 and $commentrating->dislikes == 1)
                                                <a href="/gag/comment/like/{{$post->id}}/{{$comment->id}}"><span class="glyphicon glyphicon-thumbs-up gray"></span></a>
                                                <a href="/gag/comment/dislike/{{$post->id}}/{{$comment->id}}"><span class="glyphicon glyphicon-thumbs-down red"></span></a>
                                            @else

                                                <a href="/gag/comment/like/{{$post->id}}/{{$comment->id}}"><span class="glyphicon glyphicon-thumbs-up gray"></span></a>
                                                <a href="/gag/comment/dislike/{{$post->id}}/{{$comment->id}}"><span class="glyphicon glyphicon-thumbs-down gray"></span></a>
                                            @endif
                                        No votes:
                                            @if($comment->votes <0)
                                                0
                                            @else
                                            {{$comment->votes}}
                                                @endif
                                            </li>
                                        </ul>

                                        {!! Form::open(array('url'=>'gag/response/store', 'method'=>'POST', 'class'=>'form', 'files'=>true, 'id' => 'id'.$comment->id)) !!}

                                        {!! Form::token() !!}

                                        {!! Form::hidden('comment_id', $comment->id) !!}

                                        {!! Form::hidden('post_slug', $post->slug) !!}

                                        <div class="form-group">
                                                {!! Form::label('content','Reply') !!}
                                                {!! Form::textarea('content','',['class'=>'form-control textarea-small']) !!}
                                            </div>

                                        <div class="form-group">
                                            {!!  Form::submit('Replay',['class'=> 'btn btn-default'])!!}
                                        </div>


                                        {!! Form::close() !!}


                                        <p class = 'response-btn' id="show-response-{{$comment->id}}" onclick="showResponses({{$comment->id}})">
                                            Load more replies...
                                        </p>

                                        <div id="div-{{$comment->id}}">
                                            There are no replies.
                                            @if($comment->responses == [])
                                                There are no replies.
                                                @else

                                                <ul style="list-style: none; padding: 0">
                                                    @foreach($comment->responses as $reply)
                                                        <li class="panel-body">
                                                            <div class="list-group">
                                                                <div class="list-group-item">
                                                                    <h5>{{ $reply->user->name }}</h5>
                                                                    <h6>{{ $reply->created_at->format('M d,Y \a\t h:i a') }}</h6>

                                                                </div>
                                                                <div class="list-group-item">
                                                                    <p>{!! $reply->content  !!} </p>

                                                                    @if(!Auth::guest() && ($reply->user_id == Auth::user()->id || Auth::user()->is_admin()))
                                                                        <p id='delete-response-{{$reply->id}}' class="btn btn-danger" onclick="deleteResponse({{$reply->id}})">Delete</p>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </li>
                                                    @endforeach
                                                </ul>

                                            @endif

                                                <p class = 'response-btn' id="hide-response-{{$comment->id}}" onclick="hideResponses({{$comment->id}})">
                                                    Hide replies
                                                </p>
                                        </div>
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

