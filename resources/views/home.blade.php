@extends('app')
@section('title')
    @if(!empty($title))
        {{$title}}
        @else
    Deocamdata nici o postare
    @endif
@endsection
@section('content')

    @if (empty($posts) )
        There are no posts.
        Sorry
    @else
        <div class="">
            @foreach( $posts as $post )
                <div class="list-group">
                    <div class="list-group-item">
                        <h3><a href="{{ url('/gag/'.$post->slug) }}">{{ $post->title }}</a>

                        </h3>
                        <p>{{ $post->created_at->format('M d,Y \a\t h:i a') }}
                        </p>
                    </div>
                    <div class="list-group-item">
                        <article>
                            <a href = '/gag/{{$post->slug}}'>
                                <img src="../images/catalog/{{$post->image}}"  style="height: 400px; width: 400px;"  alt="Product Image" class = 'img-responsive'>
                            </a>
                        </article>
                    </div>
                    <div class="list-group-item">
                        @if(!Auth::guest())
                            <?php $rating = \App\PostRating::where('user_id',Auth::user()->id)->where('post_id',$post->id)->first(); ?>
                            @if(empty($rating))
                                <a href="/gag/post/like/{{$post->id}}"><span class="glyphicon glyphicon-thumbs-up gray"></span></a>
                                <a href="/gag/post/dislike/{{$post->id}}"><span class="glyphicon glyphicon-thumbs-down gray"></span></a>
                            @elseif($rating->likes == 1 and $rating->dislikes == 0)
                                <a href="/gag/post/like/{{$post->id}}"><span class=" glyphicon glyphicon-thumbs-up green" ></span></a>
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

                    </div>
                </div>
            @endforeach

                {!! $posts->render() !!}

        </div>
    @endif
@endsection