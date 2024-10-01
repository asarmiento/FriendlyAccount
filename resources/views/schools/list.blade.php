@extends('layouts.school')

@section('styles')
	<style>
		a:hover{
			text-decoration: none;
		}
	</style>
@endsection

@section('content')
	<div class="row paddingWrapper">
		@if(type_user(currentUser()->type_user_id) == "Super Administrador")
		<div  class="row" style="margin: 5px">
			<a href="{{route('nueva-cuenta-cliente')}}" class="btn btn-success"> Creación Nueva Cuenta</a>
		</div>
		@endif
		@if($schools->count())
			@foreach($schools as $school)
			  	<div class="col-sm-6 col-md-4">
				    <div class="thumbnail paddingWrapper">
				    	<div class="text-center">
				      		<a class="routeSchool" href="#" data-token="{{$school->token}}" data-route="{{$school->route}}">
								@if($school->nameImage)
				      			<img src="{{ asset('images/'.$school->nameImage) }}" width="150">
								@else
								<i>No tiene logo</i>
								@endif
				      		</a>
				    	</div>
				      	<div class="caption text-center">
					        <a class="routeSchool" href="#" data-token="{{$school->token}}" data-route="{{$school->route}}">
					        	<h4>{{convertTitle($school->name)}}</h4>
					        </a>
				      	</div>
				    </div>
				</div>
	    	@endforeach
    	@else
			<h2 class="text-center">Por favor comuníquese con soporte para que le asignen una institución.</h2>
    	@endif
  	</div>

@endsection

@section('scripts')
	<script src="{{ asset('bower_components/blockUI/jquery.blockUI.js') }}"></script>
	<script src="{{ asset('bower_components/datatables/media/js/jquery.dataTables.min.js') }}"></script>
	<script src="{{ asset('bower_components/matchHeight/jquery.matchHeight-min.js') }}"></script>
	<script src="{{ asset('bower_components/bootbox/bootbox.js') }}"></script>
	<script>
		$(".message .col-md-6:first").empty();
		$(".message .col-md-6:first").html('<a href="{{URL::to('/')}}"><img src="{{ asset('images/restaurant-logo.jpg') }}" height="60px"></img></a>');
		$(".message .col-md-6").css('line-height', '4em');
		$(".message .col-md-6:first").css('padding-left', '0');
		$('.thumbnail').matchHeight();
	</script>
@endsection