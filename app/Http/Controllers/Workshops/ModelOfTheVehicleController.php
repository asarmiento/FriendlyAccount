<?php

namespace AccountHon\Http\Controllers\Workshops;

    use AccountHon\Repositories\General\BrandRepository;
    use AccountHon\Repositories\Workshops\ModelWorkshopRepository;
    use AccountHon\Traits\Convert;
    use AccountHon\Http\Controllers\Controller;

    class ModelOfTheVehicleController extends Controller
    {
        use Convert;
        /**
         * @var ModelOfTheVehicleRepository
         */
        private $modelOfTheVehicleRepository;
        /**
         * @var BrandRepository
         */
        private $brandRepository;

        /**
         * ModelOfTheVehicleController constructor.
         * @param ModelOfTheVehicleRepository $modelOfTheVehicleRepository
         * @param BrandRepository $brandRepository
         */
        public function __construct(
            ModelWorkshopRepository $modelOfTheVehicleRepository,
            BrandRepository $brandRepository
        ){

            $this->middleware('auth');
            $this->modelOfTheVehicleRepository = $modelOfTheVehicleRepository;
            $this->brandRepository = $brandRepository;
        }
        /**
         * Display a listing of the resource.
         *
         * @return Response
         */
        public function index() {
            $modelOfTheVehicles = $this->modelOfTheVehicleRepository->getModel()->whereHas('brand',function ($q){
                $q->where('school_id', userSchool()->id);
            })->get();
            return View('Workshops.ModelOfTheVehicle.index', compact('modelOfTheVehicles'));
        }

        /**
         * Show the form for creating a new resource.
         *
         * @return Response
         */
        public function create() {

            $brands = $this->brandRepository->allFilterScholl();
            return View('Workshops.ModelOfTheVehicle.create',compact('brands'));
        }

        /**
         * Store a newly created resource in storage.
         *
         * @return Response
         */
        public function store() {
            /* Capturamos los datos enviados por ajax */

            //   echo json_encode($request); die;
            $ModelOfTheVehicles = $this->convertionObjeto();

            /* Creamos un array para cambiar nombres de parametros */
            $Validation = $this->CreacionArray($ModelOfTheVehicles, 'ModelOfTheVehicle');
            $Validation['brand_id']= $this->brandRepository->token($Validation['brands'])->id;
            //echo json_encode($Validation); die;
            /* Declaramos las clases a utilizar */
            $ModelOfTheVehicle = $this->modelOfTheVehicleRepository->getModel();
            /* Validamos los datos para guardar tabla menu */
            if ($ModelOfTheVehicle->isValid($Validation)):
                $ModelOfTheVehicle->fill($Validation);
                $ModelOfTheVehicle->save();
                /* Enviamos el mensaje de guardado correctamente */
                return $this->exito('Los datos se guardaron con exito!!!');
            endif;
            /* Enviamos el mensaje de error */
            return $this->errores($ModelOfTheVehicle->errors);
        }

        /**
         * Show the form for editing the specified resource.
         *
         * @param $token
         * @return Response
         * @internal param int $id
         *
         */
        public function edit($token) {
            $modelOfTheVehicle = $this->modelOfTheVehicleRepository->token($token);

            return View('Workshops.ModelOfTheVehicle.edit', compact('modelOfTheVehicle'));
        }

        /**
         * Update the specified resource in storage.
         *
         * @param int $id
         *
         * @return Response
         */
        public function update($id) {
            /* Capturamos los datos enviados por ajax */
            $typeSeats = $this->convertionObjeto();
            /* Creamos un array para cambiar nombres de parametros */
            $Validation = $this->CreacionArray($typeSeats, 'TypeSeat');
            /** @var TYPE_NAME $Validation */
            $Validation['school_id']= userSchool()->id;
            /* Declaramos las clases a utilizar */
            $TypeSeat = $this->modelOfTheVehicleRepository->withTrashedFind($typeSeats->idTypeSeat);
            /* Validamos los datos para guardar tabla menu */
            if ($TypeSeat->isValid($Validation)):
                $TypeSeat->fill($Validation);
                $TypeSeat->save();
                /* Comprobamos si viene activado o no para guardarlo de esa manera */
                if ($typeSeats->statusTypeSeat == true):
                    /** @var TYPE_NAME $this */
                    $this->modelOfTheVehicleRepository->withTrashedFind($typeSeats->idTypeSeat)->restore();
                else:
                    /** @var TYPE_NAME $this */
                    $this->modelOfTheVehicleRepository->destroy($typeSeats->idTypeSeat);
                endif;
                /* Con este methodo creamos el archivo Json */
                $this->fileJsonUpdate();
                /* Enviamos el mensaje de guardado correctamente */
                return $this->exito('Los datos se guardaron con exito!!!');
            endif;
            /* Enviamos el mensaje de error */
            return $this->errores($TypeSeat->errors);
        }

        /**
         * Remove the specified resource from storage.
         *
         * @param int $id
         *
         * @return Response
         */
        public function destroy($id) {
            /* Capturamos los datos enviados por ajax */
            $typeSeats = $this->convertionObjeto();
            /* les damos eliminacion pasavida */
            $data = $this->modelOfTheVehicleRepository->find($id);
            if ($data):
                $data->delete();
                /* Con este methodo creamos el archivo Json */
                $this->fileJsonUpdate();
                /* si todo sale bien enviamos el mensaje de exito */
                return $this->exito('Se desactivo con exito!!!');
            endif;
            /* si hay algun error  los enviamos de regreso */
            return $this->errores($data->errors);
        }

        /**
         * Restore the specified typeuser from storage.
         *
         * @param int $id
         *
         * @return Response
         */
        public function active($id) {
            /* Capturamos los datos enviados por ajax */
            $typeSeats = $this->convertionObjeto();
            /* les quitamos la eliminacion pasavida */
            $data = $this->modelOfTheVehicleRepository->withTrashedFind($id)->restore();

            if ($data):
                /* Con este methodo creamos el archivo Json */
                $this->fileJsonUpdate();
                /* si todo sale bien enviamos el mensaje de exito */
                return $this->exito('Se Activo con exito!!!');
            endif;
            /* si hay algun error  los enviamos de regreso */
            return $this->errores($data->errors);
        }

    }

