@extends('base')

@section('page-body')

			<div class="carousel slide col-md-12 center-block" id="carousel-292986">
				<ol class="carousel-indicators">
					<li data-slide-to="0" data-target="#carousel-292986">
					</li>
					<li data-slide-to="1" data-target="#carousel-292986" class="active">
					</li>
					<li data-slide-to="2" data-target="#carousel-292986">
					</li>
				</ol>
				<div class="carousel-inner">
					@for ($i = 0; $i < 3; $i++)
						@if ($i == 0)
						    <div class="item active">
						@else
						    <div class="item ">
						@endif
							<img  alt="" style = "width: 100%;height:500px;"  src="./images/head_images/{{$data[$i]->image}}" />
							<div class="carousel-caption">
								<h4>
									<a href =  "{{ route('movie.content') }}?id={{$data[$i]->id}}">{{$data[$i]->name}}</a>
								</h4>
								<p>
									{{$data[$i]->content}}
								</p>
							</div>
						</div>
					@endfor
				</div> <a class="left carousel-control" href="#carousel-292986" data-slide="prev"><span class="glyphicon glyphicon-chevron-left"></span></a> <a class="right carousel-control" href="#carousel-292986" data-slide="next"><span class="glyphicon glyphicon-chevron-right"></span></a>
			</div>
@stop
