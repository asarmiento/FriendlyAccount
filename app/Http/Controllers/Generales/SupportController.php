<?php
namespace AccountHon\Http\Controllers\Generales;

use AccountHon\Http\Controllers\Controller;
use AccountHon\Traits\Convert;
use Illuminate\Http\Request;
use Symfony\Component\Console\Input\Input;

class SupportController extends Controller
{
    use Convert;
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        //$this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        //
    }

    /**************************************************
    * @Author: Anwar Sarmiento Ramos
    * @Email: asarmiento@sistemasamigables.com  
    * @Create: 12/07/16 07:52 PM   @Update 0000-00-00
    ***************************************************
    * @Description:
    *
    *
    *
    * @Pasos:
    *
    *
    * @return view
    ***************************************************/
    public function create()
    {
        return view('general.ticket.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        echo json_encode($request->file());
        die;
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param int $id
     *
     * @return Response
     */
    public function update($id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return Response
     */
    public function destroy($id)
    {


    }
}
