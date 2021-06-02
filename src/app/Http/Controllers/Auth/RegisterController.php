<?php

namespace App\Http\Controllers\Auth;

use Auth;
use App\Jobs\SendEmailRegisterUser;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = "/";

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        $this->validator($request->all())->validate();

        event(new Registered($user = $this->create($request->all())));

        if ($response = $this->registered($request, $user)) {
            return $response;
        }

        return $request->wantsJson()
                    ? new JsonResponse([], 201)
                    : redirect($this->redirectPath());
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'token' => Str::random(30)
        ]);

        SendEmailRegisterUser::dispatch($user);
        toast('Email confirmation has send.','info');
    }

    public function registerPenjual(Request $request){
      $request->validate([
          'name' => 'required|string|max:255',
          'email' => 'required|string|email|max:255|unique:users',
          'password' => 'required|string|min:8|confirmed',
          'ktp' => 'required|mimes:jpg,jpeg,png|max:4000',
          'no_rek' => 'required|max:255|unique:users|regex:/[0-9]/',
          'a_n' => 'required|string|max:255',
          'address' => 'required|string|min:6',
          'code_pos' => 'required|integer|min:1',
          'shipping_subdistrict_id' => 'required|exists:shipping_subdistricts,id'
      ]);

      $user = User::create([
          'name' => $request->name,
          'email' => $request->email,
          'password' => Hash::make($request->password),
          'token' => Str::random(30)
      ]);

      # save image to storage
      $KTPName = Str::random(20).'.'.$request->ktp->getClientOriginalExtension();
      $request->ktp->move(public_path('storage/ktp/'),$KTPName);

      $user->role = 'penjual';
      $user->ktp = $KTPName;
      $user->no_rek = $request->no_rek;
      $user->a_n = $request->a_n;
      $user->address = $request->address;
      $user->code_pos = $request->code_pos;
      $user->shipping_subdistrict_id = $request->shipping_subdistrict_id;
      $user->save();

      SendEmailRegisterUser::dispatch($user);
      toast('Email confirmation has send.','info');
    }

    public function verify($token,$id)
    {
      $user = User::findOrFail($id);
      if($user->token !== $token) {
          toast('Token not match!','error');
          return redirect('/');
        }
       $user->status = 1;
       $user->save();

       Auth::login($user);
       return redirect('/');
  }
}
