@extends('layouts.app')

@section('styles')
    <link rel="stylesheet" href="{{ asset('bower_components/datatables-bootstrap3-plugin/media/css/datatables-bootstrap3.css') }}">
    <style>
        .change-check{
            padding-left: 15px;
        }
        .change-check button{
            margin-left: 5px;
        }
    </style>
@endsection

@section('page')
    <aside class="page">
        <h2>Cheques</h2>
        <div class="list-inline-block">
            <ul>
                <li><a href="{{url('/')}}">Home</a></li>
                <li><a>Cheques</a></li>
                <li class="active-page"><a>Ver Cheques</a></li>
            </ul>
        </div>
    </aside>
@endsection

@section('content')
    <div class="paddingWrapper">
        <section class="row">
            <div class="table-data">
                <div class="table-header">
                    <div class="row">
                        <div class="col-sm-6">
                            <h5><strong>Lista de Cheques</strong></h5>
                        </div>
                        <div  class="col-sm-6">
                            <a href="{{route('crear-cheques')}}" class="btn btn-info pull-right">
                                <span class="glyphicon glyphicon-plus"></span>
                                <span>Nuevo</span>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="table-content">
                    <div id="appCheks" class="change-check" >
                        <input type="text" v-model="quatity"  ><button v-on:click="sendUpdate(dataNumber,quatity)" class="btn btn-success" >Cambiar Numeración</button>
                    </div>
                    <div class="table-responsive">
                        <table id="table_seatings" class="table table-bordered table-hover" cellpadding="0" cellspacing="0" border="0" width="100%">
                            <thead>
                            <tr>
                                <th>Periodo Contable</th>
                                <th>Fecha</th>
                                <th>Código</th>
                                <th>Debito</th>
                                <th>Crédito</th>
                                <th>Detalle</th>
                                <th>Monto</th>
                                <th>Reporte</th>
                                <th>Edición</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if(count($seatings)>0)
                            @foreach($seatings as $seating)
                                <tr>
                                    <td class="text-center">{{ $seating->accountingPeriods->period() }}</td>
                                    <td class="text-center">{{ $seating->date }}</td>
                                    <td class="text-center">{{ $seating->code }}</td>
                                    @if($seating->types->name == 'credito')
                                        <td class="text-center">{{ convertTitle($seating->catalogs->name) }}</td>
                                        <td class="text-center"> - </td>
                                    @else
                                        <td class="text-center"> - </td>
                                        <td class="text-center">{{ convertTitle($seating->catalogs->name) }}</td>
                                    @endif
                                    <td class="text-center">{{ convertTitle($seating->detail) }}</td>
                                    <td class="text-center">{{ $seating->amount }}</td>
                                    <td class="text-center edit-row">
                                        <a target="_blank" href="{{route('ver-asientos-excel', $seating->token)}}"><i class="fa fa-file-excel-o"></i></a>
                                    </td>
                                    <td class="text-center edit-row">
                                        <a href="{{route('asientos', $seating->token)}}"><i class="fa fa-eye"></i></a>
                                    </td>
                                </tr>
                                @foreach($seating->seatingPart as $seatingPart)
                                    @if($seatingPart->seating_id == $seating->id)
                                        <tr>
                                            <td class="text-center">{{ $seatingPart->accountingPeriods->period() }}</td>
                                            <td class="text-center">{{ $seatingPart->date }}</td>
                                            <td class="text-center">{{ $seatingPart->code }}</td>
                                            @if($seatingPart->types->name == 'credito')
                                                <td class="text-center"> - </td>
                                                <td class="text-center">{{ convertTitle($seatingPart->catalogs->name) }}</td>
                                            @else
                                                <td class="text-center">{{ convertTitle($seatingPart->catalogs->name) }}</td>
                                                <td class="text-center"> - </td>
                                            @endif
                                            <td class="text-center">{{ convertTitle($seatingPart->detail) }}</td>
                                            <td class="text-center">{{ $seatingPart->amount }}</td>
                                            <td class="text-center edit-row">
                                                <a target="_blank" href="{{route('ver-asientos-excel', $seating->token)}}"><i class="fa fa-file-excel-o"></i></a>
                                            </td>
                                            <td class="text-center edit-row">
                                                <a href="{{route('cheques', $seating->token)}}"><i class="fa fa-eye"></i></a>
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                            @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('bower_components/datatables-bootstrap3-plugin/media/js/datatables-bootstrap3.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/vue@2"></script>
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>


        var app = new Vue({
            el: '#appCheks',
            data: {
                message: 'Hello Vue!',
                dataNumber:'',
                quatity:'',
            },created(){
             axios.get("/institucion/inst/number-checks").then(response=>{
                 this.dataNumber = response.data
                 this.quatity = response.data.quatity
             })

            },
            methods:{
                sendUpdate(){
                    axios.post("/institucion/inst/store-number-checks",{quatity:this.quatity,id:this.dataNumber.id}).then(response=>{
                        swal.fire('Excelente','Se guardó con éxito','success');
                    })
                }
            }
        })


    </script>
@endsection