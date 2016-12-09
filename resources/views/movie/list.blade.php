@extends('base')

@section('page-body')

            <div class="row">
            @forelse($data as $list)
                <div class="col-md-4">
                    <div class="thumbnail">
                        <img alt="300x200" src="./images/{{$list->image}}" />
                        <div class="caption">
                            <h3>
                            <a href = "{{ route('movie.content') }}?id={{$list->id}}">{{$list->name}}</a>
                            </h3>
                            <p>
                                {{$list->content}}
                            </p>
                            <p>
                                 <a class="btn btn-primary" href="{{ route('movie.content') }}?id={{$list->id}}">详情</a>
                            </p>
                        </div>
                    </div>
                </div>
            @empty
            @endforelse
            </div>
{!! $data->links() !!}
@stop
