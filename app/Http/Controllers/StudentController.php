<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class StudentController extends Controller
{
    public function __construct()
    {
        $this->endpoint = config('app.api_url') . 'students/';
        $this->class = config('app.api_url') . 'classes/';
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $request = Http::withToken(session('token'))->get($this->endpoint);
        
        $data = [
            'title' => 'Student',
            'data' => ($request->successful() && $request->json()['status'] == 200) ? $request->json()['data'] : [],
        ];

        return view('student.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $req_class = Http::withToken(session('token'))->get($this->class);

        $data = [
            'title' => 'Student',
            'class' => ($req_class->successful() && $req_class->json()['status'] == 200) ? $req_class->json()['data'] : [],
        ];

        return view('student.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'nisn' => 'required|numeric|digits:10',
            'nis' => 'required|numeric|digits:9',
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'gender' => 'required',
            'birthplace' => 'required',
            'birthdate' => 'required',
            'class_id' => 'required'
        ]);

        try {
            $data = [
                'nisn' => $request->post('nisn'),
                'nis' => $request->post('nis'),
                'name' => $request->post('name'),
                'username' => $request->post('nisn'),
                'email' => $request->post('email'),
                'password' => $request->post('password'),
                'gender' => $request->post('gender'),
                'birthplace' => $request->post('birthplace'),
                'birthdate' => $request->post('birthdate'),
                'class_id' => $request->post('class_id')
            ];
    
            $store = Http::withToken(session('token'))->asJson()
                            ->post($this->endpoint, $data);
            if ($store->failed() || $store->json()['status'] != 200) {
                $message = ($store->json()) ? $store->json()['error'] : "Data gagal ditambahkan";
                throw new Exception($message);
            }
            responseSuccess("Data berhasil ditambahkan");
        } catch (\Throwable $th) {
            responseError($th->getMessage());
        }
        return redirect()->route('student.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $request = Http::withToken(session('token'))->get($this->endpoint . $id);
        $req_class = Http::withToken(session('token'))->get($this->class);
        $item = ($request->successful() && $request->json()['status'] == 200) ? $request->json()['data'] : [];
        if (!$item) {
            abort(404);
        }
        $data = [
            'title' => 'Student',
            'data' => $item,
            'class' => ($req_class->successful() && $req_class->json()['status'] == 200) ? $req_class->json()['data'] : [],
        ];

        return view('student.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'nisn' => 'required|numeric|digits:10',
            'nis' => 'required|numeric|digits:9',
            'name' => 'required',
            'gender' => 'required',
            'birthplace' => 'required',
            'birthdate' => 'required',
            'class_id' => 'required'
        ]);

        try {
            $data = [
                'nisn' => $request->post('nisn'),
                'nis' => $request->post('nis'),
                'name' => $request->post('name'),
                'username' => $request->post('nisn'),
                'gender' => $request->post('gender'),
                'birthplace' => $request->post('birthplace'),
                'birthdate' => $request->post('birthdate'),
                'class_id' => $request->post('class_id')
            ];
    
            $update = Http::withToken(session('token'))->asJson()
                            ->put($this->endpoint . $id, $data);
            if ($update->failed() || $update->json()['status'] != 200) {
                $message = ($update->json()) ? $update->json()['error'] : "Data gagal diubah";
                throw new Exception($message);
            }
            responseSuccess("Data berhasil diubah");
        } catch (\Throwable $th) {
            responseError($th->getMessage());
        }
        return redirect()->route('student.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $delete = Http::withToken(session('token'))->delete($this->endpoint . $id);
            if ($delete->failed() || $delete->json()['status'] != 200) {
                $message = ($delete->json()) ? $delete->json()['error'] : "Data gagal dihapus";
                throw new Exception($message);
            }
            responseSuccess("Data berhasil dihapus");
        } catch (\Throwable $th) {
            responseError($th->getMessage());
        }
        return redirect()->route('student.index');
    }
}
