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
                            <img src="../images/catalog/{{$post->image}}"  style="height: 400px; width: 400px;"  alt="Product Image" class = 'img-responsive'>
                        </article>
                    </div>
                </div>
            @endforeach

                {!! $posts->render() !!}

        </div>
    @endif
@endsection