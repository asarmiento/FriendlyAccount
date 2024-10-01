<?php

/*
  |--------------------------------------------------------------------------
  | Application Routes
  |--------------------------------------------------------------------------
  |
  | Here is where you can register all of the routes for an application.
  | It's a breeze. Simply tell Laravel the URIs it should respond to
  | and give it the controller to call when that URI is requested.
  |
 */

Route::get('/', function () {
    if(\Auth::check()){
        $type_user_id = [4,5,6];
        if(in_array(\Auth::user()->type_user_id, $type_user_id)){
            return redirect()->route('ver-salon');
        }
    }
    return redirect()->route('auth/login');
});
$whoops = new \Whoops\Run;
$whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
$whoops->register();
/* Log */
Route::get('logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index');

// Authentication routes.
Route::get('auth/login', 'Auth\AuthController@getLogin');
Route::post('auth/login', ['as' => 'auth/login', 'uses' => 'Auth\AuthController@postLogin']);
Route::get('auth/logout', ['as' => 'auth/logout', 'uses' => 'Auth\AuthController@getLogout']);

// Password reset link request routes...
Route::get('password/email', 'Auth\PasswordController@getEmail');
Route::post('password/email', 'Auth\PasswordController@postEmail');

// Password reset routes...
Route::get('password/reset/{token}', 'Auth\PasswordController@getReset');
Route::post('password/reset', 'Auth\PasswordController@postReset');

// Authentication Restaurant
Route::get('auth/restaurant', 'Auth\AuthController@getLoginRestaurant');
Route::post('auth/restaurant', ['as' => 'auth/restaurant', 'uses' => 'Auth\AuthController@postLoginRestaurant']);

/**
 * Rutas al dashboard usuarios.
 */

Route::get('pruebaFile', 'TestController@pruebadeFile');
Route::group(['prefix' => 'institucion'], function () {

    Route::get('/', ['as'=>'lista-inst','uses'=>'SchoolsController@listSchools']);
    Route::get('/nueva-cuenta-cliente', ['as'=>'nueva-cuenta-cliente','uses'=>'SchoolsController@create']);
    /* Test para hacer pruebas */
    require __DIR__.'/Routes/Code.php';
    Route::group(['prefix' =>  'inst', 'middleware'=> 'userSchool'], function () {

        $whoops = new \Whoops\Run;
        $whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
        $whoops->register();
        Route::get('test', 'TestController@index');
        Route::get('test/update/{id}', 'TestController@productPrice');
        Route::get('test/student/{year}', 'TestController@seatStudentMonth');
        Route::get('test/student/recalcular-saldo', 'TestController@recalcularSaldoStuden');
        Route::get('test/mesas', 'TestController@tableSalon');
        Route::get('test/actualizar/{id}/{other}', 'TestController@actualizarSaldoBalance');
        Route::get('test/actualizar-token/{id}', 'TestController@actualizacionToken');

        Route::get('/', ['as' => 'dashboard', function () {  return view('home'); }]);
        /**
         * Rutas del modulo Accounting
         */
        $acountings = ['Bank','Checks','buy','TypeSeat','Catalogs','Seatings', 'PaymentForm',
            'CourtCases','Suppliers','Receipt','closedYear','listOfAccount'];

        foreach($acountings AS $acounting):
            require __DIR__.'/Routes/Accounting/'.$acounting.'.php';
        endforeach;

        /**
        * Rutas de Generales
        */
        $routes = ['AccountingPeriods', 'ReportExcel','Settings','pdfReports','TypeForm',
            'support','currencies','Employess'];

        foreach($routes AS $route):
            require __DIR__.'/Routes/'.$route.'.php';
        endforeach;

        /**
         * Rutas de modulo auxiliar financiero educativo
         */
        $educations = ['Degrees','Costs','Student','AuxiliarySeat','AuxiliaryReceipt'];

        foreach($educations AS $education):
            require __DIR__.'/Routes/Auxiliary/Education/Financial/'.$education.'.php';
        endforeach;

        /**
         * Rutas de modulo auxiliar Supplier
         */
        $auxSuppliers = ['AuxiliarySupplier'];

        foreach($auxSuppliers AS $auxSupplier):
            require __DIR__.'/Routes/Auxiliary/Supplier/'.$auxSupplier.'.php';
        endforeach;

        /**
         * Rutas del modulo restraurante
         */
        $restaurants = ['InventoriesIncome','Brand','KitchenOrders','commissionAgent',
        'living','TableSalon','menu','GroupMenu', 'Cash','ClosedCash'];

        foreach($restaurants AS $restaurant):
            require __DIR__.'/Routes/Restaurant/'.$restaurant.'.php';
        endforeach;

        /**
         * Rutas del modulo Talleres
         */
        $workshops = ['ModelOfTheVehicle','Cellphone'];

        foreach($workshops AS $workshop):
            require __DIR__.'/Routes/Workshops/'.$workshop.'.php';
        endforeach;


        /**
         * Rutas del modulo Firmas Legales
         */
        $lawFirms = ['SaleOfTheFirm'];

        foreach($lawFirms AS $lawFirm):
            require __DIR__.'/Routes/LawFirms/'.$lawFirm.'.php';
        endforeach;

        /**
         * Rutas del modulo general
         */
        $Generales = ['RawMaterials','ProcessedProduct','customer','ExchangeRate','typeOfCompanies'];

        foreach($Generales AS $General):
            require __DIR__.'/Routes/General/'.$General.'.php';
        endforeach;

    });

});

/**
 *  Routes for Type User: Super Admin
 */
/**
 * Institución
 */
$routes = ['Schools','TypeUsers','Tasks','Menu','Users','Roles','TypeForm'];
/*
* Rutas de Bancos
*/
foreach($routes AS $route):
    require __DIR__.'/Routes/'.$route.'.php';
endforeach;

Route::get('usuarios', function () {
    echo "colegio/piura/usuarios";
});

Route::post('route-institucion', 'SchoolsController@routeUser');
Route::get('comprobacion', 'TestController@VerificacionSaldo');