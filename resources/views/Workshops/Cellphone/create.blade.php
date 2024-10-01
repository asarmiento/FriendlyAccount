@extends('layouts.app')

@section('styles')

	<link rel="stylesheet" href="{{ asset('bower_components/bootstrap-switch/dist/css/bootstrap3/bootstrap-switch.min.css') }}">
	<style type="text/css">
		body{
			margin:0px;
			padding:0px;
		}
		#pizarra{
			border: 1px solid  #000;
			box-shadow: 2px 2px 10px #333;
		}
	</style>
@endsection

@section('page')
	<aside class="page">
		<h2>Modelos de Celulares</h2>
		<div class="list-inline-block">
			<ul>
				<li><a href="{{url('/')}}">Home</a></li>
				<li><a>Modelos de Celulares</a></li>
				<li class="active-page"><a>Registrar Modelos de Celulares</a></li>
			</ul>
		</div>
	</aside>
@endsection

@section('content')
	<div class="paddingWrapper">
		<section class="row">
			<div class="content-box-gray col-md-12 col-sm-12">
				<div class="col-sm-9 col-md-9">
					<a href="{{route('crear-clientes')}}" class="btn btn-info" >Agregar Cliente Nuevo</a>
				</div>
				<div class="col-sm-4 col-md-4">
					<div class="form-mep">
						<label for="customerCellphone">Clientes</label>
						<div class="input-group">
							<span class="input-group-addon"><i class="fa fa-user-secret fa-lg"></i></span>
							<select id="customerCellphone" class="form-control">
								@foreach($customers AS $customer)
									<option value="{{$customer->token}}">{{$customer->nameComplete()}}</option>
								@endforeach
							</select>
						</div>
					</div>
				</div>
				<div class="col-sm-4 col-md-4">
					<div class="form-mep">
						<label for="dateOfDeliveryCellphone">Autorizado para Retirar</label>
						<div class="input-group">
							<span class="input-group-addon"><i class="fa fa-user-plus"></i></span>
							<input id="authorizedCellphone" class="form-control" type="text" >
						</div>
					</div>
				</div>
				<div class="col-sm-4 col-md-4">
					<div class="form-mep">
						<label for="dateOfDeliveryCellphone">Celular</label>
						<div class="input-group">
							<span class="input-group-addon"><i class="fa fa-user"></i></span>
							<input id="authorizedSignCellphone" class="form-control" type="text" >
						</div>
					</div>
				</div>
			</div>
			<div class="col-sm-12 col-md-12">
	<hr class="line-box-content">
	</div>
			<div class="content-box-gray col-md-12">
				<div class="col-sm-4 col-md-4">
					<div class="form-mep">
						<label for="serieCellphone">Equipo Recibido</label>
						<div class="input-group">
							<span class="input-group-addon"><i class="fa fa-barcode"></i></span>
							<select id="equipmentCellphone" class="form-control">
								<option value="">Seleccione un Equipo</option>
								<option value="Celular">Celular</option>
								<option value="Tablet">Tablet</option>
								<option value="Portatil">Portatil</option>
								<option value="Pc Escritorio">Pc Escritorio</option>
								<option value="Otro Equipo">Otro Equipo</option>
							</select>
						</div>
					</div>
				</div>
				<div class="col-sm-4 col-md-4">
				<div class="form-mep">
					<label for="brandsCellphone">Marca de Equipo</label>
					<div class="input-group">
						<span class="input-group-addon"><i class="fa fa-mobile fa-lg"></i></span>
						<select id="brandsCellphone" class="form-control">
							@foreach($brands AS $brand)
								<option value="{{$brand->token}}">{{$brand->name}}</option>
							@endforeach
						</select>
					</div>
				</div>
			</div>
			<div class="col-sm-4 col-md-4">
				<div class="form-mep">
					<label for="modelWorkshopCellphone">Modelo de Celulares</label>
					<div class="input-group">
						<span class="input-group-addon"><i class="fa fa-mobile-phone fa-lg"></i></span>
						<select id="modelWorkshopCellphone" class="form-control">
							@foreach($models AS $model)
								<option value="{{$model->token}}">{{$model->name}}</option>
							@endforeach
						</select>
					</div>
				</div>
			</div>
				<div class="col-sm-4 col-md-4">
					<div class="form-mep">
						<label for="colorCellphone">Color:</label>
						<div class="input-group">
							<span class="input-group-addon"><i class="fa fa-cab"></i></span>
							<input id="colorCellphone" placeholder="Blanco" class="form-control" type="text" >
						</div>
					</div>
				</div>
			<div class="col-sm-4 col-md-4">
				<div class="form-mep">
					<label for="serieCellphone">S/N</label>
					<div class="input-group">
						<span class="input-group-addon"><i class="fa fa-neuter"></i></span>
				      	<input id="serieCellphone" placeholder="xxxxxxxxxx" class="form-control" type="text" >
					</div>
				</div>
			</div>
				<div class="col-sm-4 col-md-4">
					<div class="form-mep">
						<label for="serieCellphone">Otro tipo de Señales:</label>
						<div class="input-group">
							<span class="input-group-addon"><i class="fa fa-stack-exchange"></i></span>
							<input id="otherTypeCellphone" placeholder="Sticker de Hello Kity" class="form-control" type="text" >
						</div>
					</div>
				</div>
				<div class="col-sm-4 col-md-4">
					<div class="form-mep">
						<label for="serieCellphone">Incluye Cargador:</label>
						<div class="input-group">
							<input id="chargerCellphone" type="checkbox" name="status-checkbox" data-on-text="Si" data-off-text="No" data-on-color="info" data-off-color="danger" data-label-text="" checked>
						</div>
					</div>
				</div>
				<div class="col-sm-4 col-md-4">
					<div class="form-mep">
						<label for="serieCellphone">S/N Cargador:</label>
						<div class="input-group">
							<span class="input-group-addon"><i class="fa fa-bar-chart"></i></span>
							<input id="chargerSeriesCellphone" placeholder="Blanco" class="form-control" type="text" >
						</div>
					</div>
				</div>
				<div class="col-sm-4 col-md-4 center">
					<div class="form-mep">
						<label for="serieCellphone">Estuche:</label>
						<div class="input-group">
							<input id="caseCellphone" type="checkbox" name="status-checkbox" data-on-text="Si" data-off-text="No" data-on-color="info" data-off-color="danger" data-label-text="" checked>
						</div>
					</div>
				</div>
				<div class="col-sm-4 col-md-4">
				<div class="form-mep">
					<label for="passwordCellphone">Contraseña/Pin del Telefono</label>
					<div class="input-group">
						<span class="input-group-addon"><i class="fa fa-diamond"></i></span>
						<input id="passwordCellphone" class="form-control" type="text" placeholder="contraseña telefono" >
					</div>
				</div>
			</div>
				<div class="col-sm-4 col-md-4">
					<div class="form-mep">
						<label for="descriptionCellphone">Otras Señas Fisicas</label>
						<div class="input-group">
							<span class="input-group-addon"><i class="fa fa-sellsy"></i></span>
							<textarea id="physicalSignsCellphone" rows="6" cols="30">

						</textarea>
						</div>
					</div>
				</div>
				<div class="col-sm-4 col-md-4">
					<div class="form-mep">
						<label for="physicalSignsCellphone">Solicitud Adicionales</label>
						<div class="input-group">
							<span class="input-group-addon"><i class="fa fa-angellist"></i></span>
							<textarea id="additionalRequestsCellphone" rows="6" cols="30">

						</textarea>
						</div>
					</div>
				</div>
				<div class="col-sm-12 col-md-12">
					<div class="form-mep">
						<label for="descriptionCellphone">Problema Reportado</label>
						<div class="input-group">
							<span class="input-group-addon"><i class="fa fa-eraser"></i></span>
							<textarea id="reportedProblemsCellphone" rows="6" cols="112">

						</textarea>
						</div>
					</div>
				</div>

			</div>
			<div class="content-box-gray">
			<div class="col-sm-4 col-md-4">
				<div class="form-mep">
					<label for="priorityCellphone">Prioridad </label>
					<div class="input-group">
						<span class="input-group-addon"><i class="fa fa-signal"></i></span>
						<select id="priorityCellphone" class="form-control">
							<option value="baja">Baja</option>
							<option value="medio">Medio</option>
							<option value="alta">Alta</option>
						</select>
					</div>
				</div>
			</div>

			<div class="col-sm-4 col-md-4">
				<div class="form-mep">
					<label for="dateOfReceiptCellphone">Fecha de Recibido</label>
					<div class="input-group">
						<span class="input-group-addon"><i class="fa fa-calendar-o"></i></span>
						<input id="dateOfReceiptCellphone" readonly class="form-control" type="date" value="{{date('Y-m-d')}}" >
					</div>
				</div>
			</div>
			<!--div class="col-sm-4 col-md-4">
					<div class="form-mep">
						<label for="descriptionCellphone">Firma</label>
						<div class="input-group">
							<span class="input-group-addon"><i class="fa fa-pencil"></i></span>

							<canvas id="pizarra" width="300px" height="200px" onclick="init()"></canvas>
						</div>
					</div>
				</div-->

			</div>
		</section>
		<div class="row text-center">
			<a href="{{route('ver-taller-de-celulares')}}" class="btn btn-default"><span class="glyphicon glyphicon-circle-arrow-left"></span>Regresar</a>
			<a href="#" id="saveCellphone" data-url="taller-de-celulares" class="btn btn-success">Grabar Modelo</a>
		</div>
	</div>
@endsection

@section('scripts')
<script type="text/javascript">
    $(document).ready(function () {



    var pizarra_canvas
    var pizarra_context

	/*
	 Inicializamos la pizarra.
	 En primer lugar comprobamos si el navegador tiene soporte para canvas utilizando la
	 librería Modernizr.
	 Después guardamos referencia al canvas y definimos el color del trazo con el que vamos a dibujar.
	 Por último, añadimos listeners para los eventos "mousedown" y "mouseup", ya que cuando salten esos
	 eventos tenemos que empezar / terminar de pintar
	 */

    function init(){
        if(!Modernizr.canvas){
            document.getElementById("contenedor_pizarra").style.display = "none";
        }else{
            document.getElementById("no_html5").style.display = "none";
            pizarra_canvas = document.getElementById("pizarra");
            pizarra_context = pizarra_canvas.getContext("2d");
            pizarra_context.strokeStyle = "#000";
            pizarra_canvas.addEventListener("mousedown",empezarPintar,false);
            pizarra_canvas.addEventListener("mouseup",terminarPintar,false);
        }
    }

	/*
	 empezarPintar(e)
	 Al hacer mousedown sobre la pizarra, comenzamos un nuevo trazo, movemos el pincel hasta la
	 posición del ratón y añadimos un listener para el evento mousemove, para que con cada movimiento
	 del ratón se haga un nuevo trazo
	 */


    function empezarPintar(e){
        pizarra_context.beginPath();
        pizarra_context.moveTo(e.clientX-pizarra_canvas.offsetLeft,e.clientY-pizarra_canvas.offsetTop);
        pizarra_canvas.addEventListener("mousemove",pintar,false)
    }

	/*
	 terminarPintar(e) se ejecuta al soltar el botón izquierdo, y elimina el listener para
	 mousemove
	 */

    function terminarPintar(e){
        pizarra_canvas.removeEventListener("mousemove",pintar,false);
    }

	/*
	 pintar(e) se ejecuta cada vez que movemos el ratón con el botón izquierdo pulsado.
	 Con cada movimiento dibujamos una nueva linea hasta la posición actual del ratón en pantalla.
	 */

    function pintar(e) {
        pizarra_context.lineTo(e.clientX-pizarra_canvas.offsetLeft,e.clientY-pizarra_canvas.offsetTop);
        pizarra_context.stroke();
    }

	/*
	 borrar() vuelve a setear el ancho del canvas, lo que produce que se borren los trazos dibujados
	 hasta ese momento.
	 */

    function borrar(){
        pizarra_canvas.width = pizarra_canvas.width;
    }
    });
</script>

	<script src="{{ asset('js/pages/workshops/cellphone.js') }}"></script>
	<script src="{{ asset('bower_components/bootstrap-switch/dist/js/bootstrap-switch.min.js') }}"></script>
@endsection