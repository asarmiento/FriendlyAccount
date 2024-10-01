@include('layouts.partials.etiquetas')
<body>
	<nav class="Menu">
		<div class="Menu-logo">
			<figure>
				@if(userSchool())
					@if(strlen(userSchool()->nameImage)>0)
						<a href="{{ route('lista-inst') }}"><img class="center-block"  src="{{ asset('images/'.userSchool()->nameImage) }}"></a>
					@else
						<a href="{{ route('lista-inst') }}"><img class="center-block" src="{{ asset('images/favicon.png') }}"></a>
					@endif
				@endif
			</figure>
		</div>
		@include('layouts.partials.menu')
	</nav>
	<section class="content-wrapper">
		@include('layouts.partials.header')
		@yield('page')
		@yield('content')
	</section>
	<!-- Scripts -->
	<script src="{{ asset('bower_components/jquery/dist/jquery.min.js') }}"></script>
	<script src="{{ asset('bower_components/bootstrap/dist/js/bootstrap.min.js') }}"></script>
	<script src="{{ asset('bower_components/blockUI/jquery.blockUI.js') }}"></script>
	<script src="{{ asset('bower_components/bootbox/bootbox.js') }}"></script>
	<script src="{{ asset('bower_components/datatables/media/js/jquery.dataTables.min.js') }}"></script>
	<script src="{{ asset('js/app.js') }}"></script>
	@yield('scripts')

</body>
</html>