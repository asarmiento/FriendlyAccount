<section class="message border-bottom">
	<div class="row">
		<div class="col-md-4">
			<a href="#"><span class="glyphicon glyphicon-th-list"></span></a>
			@if(userSchool())
				<span>{{convertTitle(userSchool()->name)}}</span>
				<span> - {{ period() }}</span>
			@endif

		</div>
		{{--<div class="col-md-3">
			<a href="{{route('support-report')}}">Ticket <i class="fa fa-cog" aria-hidden="true"></i></a>
		</div>--}}
		<div class="col-md-8">
			<div class="pull-right">
				<div class="list-inline-block">
					<ul>
						<li><a>Bienvenido {{ Auth::user()->nameComplete() }} - {{ convertTitle(Auth::user()->typeUsers->name) }}</a></li>
						<li><a href="#"><span class="glyphicon glyphicon-envelope"></span></a></li>
						<li><a href="{{ url('/auth/logout') }}"><strong>| Cerrar Sesión</strong></a></li>
					</ul>
				</div>
			</div>
		</div>
	</div>
</section>