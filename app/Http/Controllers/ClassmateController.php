<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ClassmateController extends Controller
{
    public function __construct()
    {
        $this->endpoint = config('app.api_url') . 'students/';
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $class = session('class_id'); // Later with session

        $request = Http::withToken(session('token'))->get($this->endpoint . 'findByClass/' . $class);
        
        $data = [
            'title' => 'Classmate',
            'data' => ($request->successful() && $request->json()['status'] == 200) ? $request->json()['data'] : [],
        ];

        return view('classmate.index', $data);
    }
}
