<?php

namespace App\Http\Controllers\Auth;

use App\Entities\User;
use App\Http\Controllers\Controller;
use Doctrine\ORM\EntityManager;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Ramsey\Uuid\Uuid;

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
    protected $redirectTo = '/home';

    /**
     * @var EntityManager
     */
    private $em;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(EntityManager $em)
    {
        $this->middleware('guest');
        $this->em = $em;
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        // Minimum pw length of 4 because this is just in test phase
        // this will certainly be increased when it is needed :)

        return Validator::make($data, [
            'firstName' => 'required|string|max:255',
            'lastName' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:' . User::class,
            'password' => 'required|string|min:4|confirmed',

        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
        $username = strtolower($data['firstName'][0] . $data['lastName']);

        $user = new User(
            Uuid::uuid4(),
            $username,
            $data['firstName'],
            $data['lastName'],
            $data['email'],
            $data['password']
        );

        $this->em->persist($user);

        return $user;
    }
}