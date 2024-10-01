@extends('layouts.app')

@section('styles')
    @parent
    {!! asset('css/plugins/foundation-datepicker.css') !!}}
@stop

@section('content')
    @parent
    @section('principal')
        @parent
        <h3>Generación de códigos</h3>
        <div class="panel" id="Report">
            <form method="post" action="{{route('code.store')}}">
			  	<div class="row">
				    <div class="large-4 columns">
				      	<label>Código
				        	<input name="code" type="text" placeholder="123400" maxlength="6" />
				      	</label>
				    </div>
				    <div class="large-4 columns">
				      	<label>Fecha de expiración
				        	<input name="date" type="text" class="span2" value="" id="dpd1">
				      	</label>
				    </div>
				    <div class="large-4 columns">
				    	<label for="">&nbsp;</label>
				      	<button type="submit" class="button tiny success">Guardar</button>
				    </div>
			  	</div>
			</form>
			@if($errors->any())
			  	<div data-alert class="alert-box info">
					<a href="#" class="close">&times;</a>
					@foreach($errors->all() as $error)
						{{ $error }}
					@endforeach
				</div>
			@endif
			<table id="table">
				<thead>
					<tr>
						<td>Fecha de Expiración</td>
						<td>Código</td>
						<td>Fecha de creación</td>
					</tr>
					@if($codes->count())
						@foreach($codes as $code)
							<tr>
								<td>{{\Carbon\Carbon::parse($code->expiration)->format('d-m-Y')}}</td>
								<td>{{ $code->code }}</td>
								<td>{{\Carbon\Carbon::parse($code->created_at)->format('d-m-Y')}}</td>
							</tr>
						@endforeach
					@endif
				</thead>
			</table>
        </div>
    @stop
@stop

@section('scripts')
    @parent
    {!! asset('js/plugins/foundation-datepicker.js') !!}
    <script>
    	$('#dpd1').fdatepicker({
            format:'dd-mm-yyyy'});
    </script>
@stop