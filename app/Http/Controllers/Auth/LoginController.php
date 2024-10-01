<?php


namespace AccountHon\Http\Controllers\Auth;


use AccountHon\Entities\General\UserSession;
use AccountHon\Entities\User;
use AccountHon\Http\Controllers\Controller;
use AccountHon\Http\Requests\AuthRequest;
use AccountHon\Repositories\Restaurant\TableSalonRepository;
use AccountHon\Repositories\SchoolsRepository;
use AccountHon\Repositories\UsersRepository;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;
use Illuminate\Validation\Validator;

class LoginController extends Controller
{
    /*
        |--------------------------------------------------------------------------
        | Login Controller
        |--------------------------------------------------------------------------
        |
        | This controller handles authenticating users for the application and
        | redirecting them to your home screen. The controller uses a trait
        | to conveniently provide its functionality to your applications.
        |
        */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/institucion';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        UsersRepository $usersRepository,
        TableSalonRepository $tableSalonRepository,
        SchoolsRepository $schoolsRepository
    )
    {
        $this->middleware('guest', ['except' => 'logout']);
        $this->usersRepository = $usersRepository;
        $this->tableSalonRepository = $tableSalonRepository;
        $this->schoolsRepository = $schoolsRepository;
    }

    public function getCredentials(Request $request)
    {
        $request->merge([
            'status' => 1,
        ]);
        $this->request = $request;
        return $request->only($this->loginUsername(),'password','status','session');
    }
    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data) {
        return Validator::make($data, [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|confirmed|min:6',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data) {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);
    }

    /**
     * [getLogout description]
     * @return [type] [description]
     */
    public function logout(){
        \Session::forget('school');
        $redirect = '/';
        // Type Users Restaurant
        $type_users_restaurant = [4,5,6];
        if(Auth::check())
        {
            if(in_array(Auth::user()->type_user_id, $type_users_restaurant))
            {
                $redirect = "/auth/restaurant";
            }
        }
        Auth::logout();
        return redirect($redirect);
    }
    /**************************************************
     * @Author: Francisco Gamonal
     * @Aplicado: Anwar Sarmiento Ramos
     * @Email: asarmiento@sistemasamigables.com
     * @Create: 2016-11-04 6:00am   @Update 0000-00-00
     ***************************************************
     * @Description:
     *
     *
     *
     * @Pasos:
     *
     *
     * #if (${TYPE_HINT} != "void") * @return ${TYPE_HINT}
     *  #end
     *  ${THROWS_DOC}
     ***************************************************/
    public function login(AuthRequest $request){
        $credentials = $this->getCredentials($request);

        $data = array('email' => $credentials['email'], 'password' => $credentials['password']);

        if (\Auth::attempt($data, $request->has('remember'))) {
            $validate = $this->validateUserSession($credentials['session']);

            if(strlen($validate) > 0){
                return redirect($this->loginPath())
                    ->withInput($request->only($this->loginUsername(), 'remember'))
                    ->withErrors([
                        $validate
                    ]);
            }

            return redirect('/institucion');
        }

        return redirect($this->loginPath())
            ->withInput($request->only($this->loginUsername(), 'remember'))
            ->withErrors([
                $this->loginUsername() => Lang::has('auth.failed')
                    ? Lang::get('auth.failed')
                    : 'Estas credenciales no coinciden con nuestros registros.',
            ]);
    }

    private function validateUserSession($session){
        $code = sha1(md5($session));
        $userSession = UserSession::where('code',$code)->first();

        if( ! $userSession ):
            \Auth::logout();
            return "El código ingresado no existe";
        endif;

        if(Carbon::now()->format('Y-m-d') > Carbon::parse($userSession->expiration)->format('Y-m-d')):
            \Auth::logout();
            return "El código ha Vencido, Contactar a Soporte (506)8304-5030";
        endif;
    }
    /**
     *
     */
    public function getLoginRestaurant()
    {
        return view('auth.login_restaurant');
    }
    /**
     *
     */
    public function getLogin()
    {
        return view('auth.login');
    }

    /**
     *
     */
    public function postLoginRestaurant(Request $request)
    {
        $users = $this->usersRepository->getModel()->whereIn('type_user_id', [4,5,6])->get();

        foreach ($users as $key => $user) {
            if(Auth::attempt(['email' => $user->email, 'password' => $request->password])){
                $user_login = $user;
                break;
            }
        }

        if(isset($user_login)){
            // Template Moso o Cajero
            Auth::loginUsingId($user_login->id);
            $user =   \AccountHon\Entities\User::with('schools')->find($user_login->id);
            // School debería ser dinámico
            $school = $this->schoolsRepository->find($user->schools[0]->id);
            \Session::put('school', $school);
            return redirect('/institucion/inst/salon');
        }else{
            $users = $this->usersRepository->getModel()->whereIn('type_user_id', [1,2,3,4])->get();
            foreach ($users as $key => $user) {
                if(Auth::attempt(['email' => $user->email, 'password' => $request->password])){
                    $user_login = $user;
                    break;
                }
            }
            if(isset($user_login)){
                // Template Moso o Cajero
                Auth::loginUsingId($user_login->id);
                // School debería ser dinámico
                if(isset(Auth::user()->schools[0]))
                {
                    $school = Auth::user()->schools[0];
                }
                \Session::put('school', $school);
                return redirect('/institucion/inst/salon');
            }else{
                // Error for login
                return redirect()->back()->withErrors('Las credenciales no coinciden.');
            }
        }
    }
}
