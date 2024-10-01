@extends('login')
@section('styles')
<link rel="stylesheet" type="text/css" href="{{asset('css/custom-login.css')}}">
@endsection
@section('content')


<div class="container-fluid margin-top-login margin-bottom-login">
	<div class="row">
		<div class="col-md-4 col-md-offset-4">
			<div class="panel box-login">
				<div class="panel-heading text-center">
					<img src="../images/logo-sa.jpg">
				</div>
				<div class="panel-body">
					@if (count($errors) > 0)
						<div class="alert alert-danger">
							<strong>Whoops!</strong> There were some problems with your input.<br><br>
							<ul>
								@foreach ($errors->all() as $error)
									<li>{{ $error }}</li>
								@endforeach
							</ul>
						</div>
					@endif
                    <form class="form-horizontal" role="form" method="POST" action="/auth/login">
						{{csrf_field()}}

						<div class="container-fluid padding-15-l-r">
							<div class="form-group panel panel-default panel-primary-login">
								<label class="control-label text-uppercase"><h6><strong>E-Mail</strong></h6></label>
								<input type="email" class="form-control form-control-login" name="email" value="{{ old('email') }}">
							</div>
						
							<div class="form-group panel panel-default panel-primary-login">
								<label class="control-label text-uppercase"><h6><strong>Contraseña</h6></strong></label>
								<input type="password" class="form-control form-control-login" name="password">
							</div>

							<div class="form-group panel panel-default panel-primary-login">
								<label class="control-label text-uppercase"><h6><strong>Código</h6></strong></label>
								<input type="password" class="form-control form-control-login" name="session">
							</div>

							<div class="form-group">
								<div class="checkbox">
									<label>
										<input type="checkbox" name="remember"> <h6><strong>Remember Me</h6></strong>
									</label>
								</div>
							</div>

							<div class="form-group">
								<button type="submit" class="btn btn-primary btn-lg btn-primary-login btn-block" style="margin-right: 15px;">
									Iniciar Sesión
								</button>
							</div>



						</div>

					</form>



				</div>


			</div>

			<div class="form-group">
							<div class="col-md-6">
								<a href="/auth/restaurant"> Restaurante </a>
							</div>
							<div class="col-md-6 text-right">
								<a href="/password/email">Forgot Your Password?</a>
							</div>
						</div>
		</div>
	</div>
</div>
@endsection
