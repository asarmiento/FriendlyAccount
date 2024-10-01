<?php
namespace AccountHon\Http\Controllers\Auth;

use AccountHon\Entities\General\UserSession;
use AccountHon\Http\Controllers\Controller;
use AccountHon\Traits\Convert;
use Exception;
use Illuminate\Http\Request;

class CodeController extends Controller {

    use Convert;
	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$codes = UserSession::all();
		return view('code.index', compact('codes'));
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		//
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @param CashDeskSaveRequest $request
	 * @return Response
	 */
	public function store(Request $request)
	{
		$code = $request['code'];
		$date = $request['date'];
		if($code && $date){
			$code = sha1(md5($code));
			$new_code = new UserSession;
			$new_code->code = $code;
			$new_code->expiration = \Carbon\Carbon::parse($date);
			$new_code->save();
			return redirect()->back()->withErrors('Se guardaron los cambios correctamente.');
		}
		return redirect()->back()->withErrors('Necesita ingresar los campos: código y fecha.');
	}


	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
	}
}
