<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Airlock\HasApiTokens;
use DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'login', 'blocked', 'group_id', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public static function getUsersList ($request) {
        
        $filters = json_decode($request->filters);
        $filter = array();
        if ($filters->text != null) {
            $filter['name'] = $filters->text;
        }

        $users = User::join('users_info', 'users.id', '=', 'users_info.user_id')
        ->where(function($query) use ($filter) {
            foreach ($filter as $k => $v) {
                if ($k == 'name') {
                    $query->where('users.email', 'like', $v.'%');
                    $query->orWhere('users.login', 'like', $v.'%');
                    $query->orWhere('users_info.first_name', 'like', $v.'%');
                    $query->orWhere('users_info.last_name', 'like', $v.'%');
                }
            }
        })
        ->distinct()
        ->paginate(10);
        $count = User::count();
        return json_encode(['users' => $users, 'count' => $count]);
    }

    public static function getUserForEdit ($id) {
        $users = User::find($id);
        $users_info = DB::table('users_info')->where('user_id', '=', $id)->first();
        return json_encode(['users' => $users, 'users_info' => $users_info]);
    }

    public function addUserFromAdmin ($request) {

        $this->validator($request->users)->validate();
        $user = $this->addFromAdmin($request->users);

        if ($user->id != null) {
            DB::table('users_info')->insert([
                'user_id' => $user->id,
                'first_name' => $request->users_info['first_name'],
                'last_name' => $request->users_info['last_name'],
                'avatar' => $request->users_info['avatar'],
                'birthday_data' => $request->users_info['birthday_data'],
                'gender' => $request->users_info['gender'],
                'address' => $request->users_info['address'],
                'country_id' => $request->users_info['country_id'],
                'city_id' => $request->users_info['city_id'],
                'age' => $request->users_info['age'],
                'about' => $request->users_info['about'],
                'phone' => $request->users_info['phone']
                ]);
        }

        return json_encode(['success'=> true, 'result' => 'пользователь был добавлен', 'user' => $user->id]);

    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'login' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:4', 'confirmed'],
        ]);
    }

    protected function addFromAdmin(array $data)
    {
        return User::create([
            'login' => $data['login'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'group_id' =>$data['group_id'],
            'blocked' =>$data['blocked'],
        ]);
    }

    public function register($request)
    {
        $this->validator($request->all())->validate();

        $user = $this->add($request->all());

        $this->guard()->login($user);
        DB::table('users_info')->insert([
            'user_id' => $user->id
            ]);
        return response()->json(['user'=> $user,
            'message'=> 'registration successful'
        ], 200);
    }

    protected function add(array $data)
    {
        return User::create([
            'login' => $data['login'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'group_id' => 1,
            'blocked' => 0,
        ]);
    }

    public function login($request)
    {
        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials)) {
            return response()->json(['message' => 'Login successful'], 200);
        }
    }
    

}
