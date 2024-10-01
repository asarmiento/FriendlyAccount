<?php

namespace AccountHon\Http\Controllers;

use AccountHon\Entities\Accounting\AccountControl;
use AccountHon\Entities\AccountingPeriod;
use AccountHon\Entities\Seating;
use AccountHon\Entities\SeatingPart;
use AccountHon\Repositories\AccountingPeriodRepository;
use AccountHon\Repositories\SettingRepository;
use AccountHon\Traits\Convert;
use Illuminate\Http\Request;
use AccountHon\Http\Requests;
use AccountHon\Http\Controllers\Controller;
use AccountHon\Repositories\CatalogRepository;
use AccountHon\Repositories\TypeSeatRepository;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;

class SettingsController extends Controller {

    use Convert;
    /**
     * @var CatalogRepository
     */
    private $catalogRepository;

    /**
     * @var TypeSeatRepository
     */
    private $typeSeatRepository;

    /**
     * @var SettingRepository
     */
    private $settingRepository;

    /**
     * @var AccountingPeriodRepository
     */
    private $accountingPeriodRepository;


    /**
     * @param CatalogRepository          $catalogRepository
     * @param TypeSeatRepository         $typeSeatRepository
     * @param SettingRepository          $settingRepository
     * @param AccountingPeriodRepository $accountingPeriodRepository
     */
    public function __construct(
    CatalogRepository $catalogRepository,
    TypeSeatRepository $typeSeatRepository,
    SettingRepository $settingRepository,
    AccountingPeriodRepository $accountingPeriodRepository
    ) {

        $this->catalogRepository = $catalogRepository;
        $this->typeSeatRepository = $typeSeatRepository;
        $this->settingRepository = $settingRepository;
        $this->accountingPeriodRepository = $accountingPeriodRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index() {
        $typeSeat = $this->typeSeatRepository->lists('id');
        $settings = $this->settingRepository->whereInSingle('type_seat_id',$typeSeat);
        return View('settings.index', compact('settings'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create() {
        $catalogs = $this->catalogRepository->getModel()->where('style', 'Detalle')->where('school_id', userSchool()->id)->orderBy('code', 'ASC')->get();
        $typeSeat = $this->typeSeatRepository->whereDuoData('CorCa');

        $setting = $this->settingRepository->getModel()->where('type_seat_id', $typeSeat[0]->id)->get();
        
        return View('settings.create', compact('catalogs', 'typeSeat', 'setting'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store() {
        $settings = $this->convertionObjeto();
        $typeSeat = $this->typeSeatRepository->token($settings->typeSeatSetting);
        $catalog = $this->catalogRepository->token($settings->catalogSetting);
        $Validation['type_seat_id'] = $typeSeat->id;
        $Validation['catalog_id'] = $catalog->id;
        $Validation['token'] = Crypt::encrypt($catalog->id);
        $settingVerif = $this->settingRepository->getModel()->where('type_seat_id', $typeSeat->id)->get();
        
        if (!$settingVerif->isEmpty()):
            return $this->errores(['setting save' => 'Ya existe la configuracion para ' . $typeSeat->name]);
        endif;
        /* Validamos los datos para guardar tabla menu */
        $setting = $this->settingRepository->getModel();
        if ($setting->isValid($Validation)):
            $setting->fill($Validation);
            $setting->save();

            return $this->exito('Se Guardo con exito la cuenta');
        endif;

        return $this->errores($setting);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $token
     * @return Response
     */
    public function edit($token) {

        $setting = $this->settingRepository->token($token);
        $catalogs = $this->catalogRepository->getModel()->where('style', 'Detalle')->where('school_id', userSchool()->id)->orderBy('code', 'ASC')->get();

        return View('settings.edit', compact('setting', 'catalogs'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update() {
        $settings = $this->convertionObjeto();

        $setting = $this->settingRepository->token($settings->token);
        $typeSeat = $this->typeSeatRepository->token($settings->typeSeatSetting);
        $catalog = $this->catalogRepository->token($settings->catalogSetting);
        $Validation['type_seat_id'] = $typeSeat->id;
        $Validation['catalog_id'] = $catalog->id;
        /* Validamos los datos para guardar tabla menu */
        if ($setting->isValid($Validation)):
            $setting->fill($Validation);
            $setting->save();

            return $this->exito('Se Actualizo con exito la cuenta');
        endif;

        return $this->errores($setting);
    }


    public function conciliacion() {
        $controls = AccountControl::whereHas('catalog',function ($q){
            $q->where('school_id',userSchool()->id);
        })->with('catalog')->get();

        $periods = $this->accountingPeriodRepository->allFilterScholl() ;
        return view('seatings.conciliacion',compact('periods','controls'));
    }

    public function postConciliacion()
    {
        $data = Input::all();
        $auxiliar = AccountControl::where('id',$data['auxiliar'])->get();
        $balanceAccount = (Seating::where('catalog_id',$auxiliar[0]->catalog_id)
            ->where('status','Aplicado')
            ->where('type_id',6)->sum('amount') + SeatingPart::where('catalog_id',$auxiliar[0]->catalog_id)
                ->where('status','Aplicado')
                ->where('type_id',6)->sum('amount'))-(Seating::where('catalog_id',$auxiliar[0]->catalog_id)
                    ->where('status','Aplicado')
                    ->where('type_id',7)->sum('amount') + SeatingPart::where('catalog_id',$auxiliar[0]->catalog_id)
                    ->where('status','Aplicado')
                    ->where('type_id',7)->sum('amount'));
        $accounting = Seating::select('code',DB::raw('SUM(amount) AS amount '),'type_id','detail','date')
            ->where('catalog_id',$auxiliar[0]->catalog_id)
            ->where('status','Aplicado')
            ->where('accounting_period_id',$data['period'])->groupBy('type_id')->groupBy('code')->get();
        $accountingPart = SeatingPart::select('code',DB::raw('SUM(amount) AS amount '),'type_id','detail','date')
            ->where('catalog_id',$auxiliar[0]->catalog_id)
            ->where('status','Aplicado')
            ->where('accounting_period_id',$data['period'])->groupBy('type_id')->groupBy('code')->get();

        $tableAuxiliar = DB::table($auxiliar[0]->auxiliary_table_name)
            ->select('code',DB::raw('SUM(amount) AS amount '),'type_id','detail','date')
            ->where('accounting_period_id',$data['period'])
            ->where('status','aplicado')
            ->groupBy('type_id')->groupBy('code')->orderBy('code')->get();
        $period = AccountingPeriod::where('school_id',userSchool()->id)->lists('id');
      //  echo $period; die;
        $balanceAux = DB::table($auxiliar[0]->auxiliary_table_name)
            ->where('type_id',7)->where('accounting_period_id',$data['period'])

                ->where('status','aplicado')
            ->sum('amount')-DB::table($auxiliar[0]->auxiliary_table_name)
                ->where('type_id',6)->whereIn('accounting_period_id',[$period])

                ->where('status','aplicado')
                ->sum('amount');
        return view('seatings.listaConciliacion',compact('accounting', 'tableAuxiliar',
            'auxiliar',
            'balanceAccount',
            'balanceAux',
            'accountingPart'));
    }
}
