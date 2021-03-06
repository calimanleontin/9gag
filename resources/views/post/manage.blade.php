@extends('app')
@section('title')
    @if(!empty($title))
        {{$title}}
    @else
        Deocamdata nici o postare
    @endif
@endsection
@section('content')
    @if (!empty($posts[0]))

            @foreach( $posts as $post )
                <div class="list-group">
                    <div class="list-group-item">
                        <h3><a href="{{ url('/'.$post->slug) }}">{{ $post->title }}</a>

                        </h3>
                        <p>{{ $post->created_at->format('M d,Y \a\t h:i a') }}
                        </p>
                    </div>
                    <div class="list-group-item">
                        <article>
                            <img src="../images/catalog/{{$post->image}}"  style="height: 400px; width: 400px;"  alt="Product Image" class = 'img-responsive'>
                        </article>
                        <a href="/post/accept/{{$post->id}}"><button class="btn btn-success">Accept </button></a>
                        <a href="/post/decline/{{$post->id}}"><button class="btn btn-danger">Delete </button></a>

                    </div>
                </div>
            @endforeach

            {!! $posts->render() !!}
        @else
        Sorry, no photos

    @endif
@endsection