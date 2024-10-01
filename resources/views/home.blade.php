@extends('layouts.app')

@section('page')
	<aside class="page"> 
		<h2>Inicio</h2>
		<div class="list-inline-block">
			<ul>
				<li class="active"><a href="{{URL::to('/')}}">Inicio</a></li>
			</ul>
		</div>
	</aside>
@endsection

@section('content')
	@if(userSchool()->type == 'restaurant')
		<div class="paddingWrapper" style="background-image: url('/images/restaurant.jpg');height: 100vh;">
		</div>
	@elseif(userSchool()->type=='bufete')
		<div class="paddingWrapper" style="background-image: url('/images/bufete.jpg');height: 90vh; no-repeat center center fixed;
-webkit-background-size: cover;
-moz-background-size: cover;
-o-background-size: cover;
background-size: cover;">
		</div>
	@else
		<div class="paddingWrapper" >
		</div>
	@endif



@stop