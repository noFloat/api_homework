@extends('base')

@section('page-body')
	<div class ="col-md-4" >
		<img src="./images/{{$data[0]->image}}" class="img-responsive" alt="Responsive image">
	</div>
	<div class = "col-md-8">
		<div class = "col-md-8">
			<p class="bg-primary">影片名字：{{$data[0]->name}}</p>
			<p class="bg-success">导演：{{$data[0]->author}}</p>
			<p class="bg-info">演员：{{$data[0]->actor}}</p>
			<h2>内容简介：{{$data[0]->content}}</h2>
		</div>
	</div>
@stop
