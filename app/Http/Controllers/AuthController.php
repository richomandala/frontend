<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->endpoint = config('app.api_url') . 'auth/login/';
    }

    public function login()
    {
        return view('auth.login');
    }

    public function loginPost(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required'
        ]);

        try {
            $data = [
                'username' => $request->post('email'),
                'password' => $request->post('password')
            ];
    
            $login = Http::asJson()
                            ->post($this->endpoint, $data);
            if ($login->failed() || $login->json()['status'] != 200) {
                $message = ($login->json()) ? $login->json()['error'] : "Wrong Username / Password";
                throw new Exception($message);
            }
            $data = $login->json()['data'];
            
            session()->put('token', $data['token']);
            session()->put('user_id', $data['user_id']);
            session()->put('role_id', $data['role_id']);
            session()->put('username', $data['username']);
            session()->put('email', $data['email']);
            if ($data['role_id'] == 2) {
                session()->put('name', $data['name']);
                session()->put('teacher_id', $data['teacher_id']);
            } elseif ($data['role_id'] == 3) {
                session()->put('name', $data['name']);
                session()->put('student_id', $data['student_id']);
                session()->put('class_id', $data['class_id']);
            }
            return redirect()->to('/');
        } catch (\Throwable $th) {
            responseError($th->getMessage());
            return redirect()->to('auth/login');
        }
    }
}
