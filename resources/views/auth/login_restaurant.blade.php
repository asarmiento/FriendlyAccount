 @extends('login')

 @section('styles')
	<link rel="stylesheet" type="text/css" href="{{ asset('css/restaurant.css') }}">
 @stop

 @section('content')
 	<section class="Login">
		<article class="Login-Form text-center">
			<form action="{{route('auth/restaurant')}}" role="form" method="POST">
				<input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
				<h1>Código PIN</h1>
				<div class="input-group input-group-lg">
				  	<span class="input-group-addon" id="sizing-addon1"><span class="glyphicon glyphicon-lock"></span></span>
				  	<input type="password" class="form-control" name="password" aria-describedby="sizing-addon1">
				</div>
				<br>
				<button type="submit" class="btn btn-default color-black margin-bottom-1">INGRESAR</button>
				<a href="/auth/login" class="btn btn-primary margin-bottom-1">Administración</a>
				@if($errors->count())
					<div class="alert alert-danger alert-dismissible" role="alert">
						@foreach($errors->all() as $error)
							<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						  	<strong>{{$error}}</strong>
						@endforeach
					</div>
				@endif
			</form>
		</article>
 	</section>
 @stop

 @section('scripts')

 @stop