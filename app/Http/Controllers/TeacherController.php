<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class TeacherController extends Controller
{
    public function __construct()
    {
        $this->endpoint = config('app.api_url') . 'teachers/';
        $this->day = config('app.api_url') . 'days/';
        $this->time = config('app.api_url') . 'times/';
        $this->schedule = config('app.api_url') . 'schedules/';
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
            'title' => 'Teacher',
            'data' => ($request->successful() && $request->json()['status'] == 200) ? $request->json()['data'] : [],
        ];

        return view('teacher.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data = [
            'title' => 'Teacher'
        ];

        return view('teacher.create', $data);
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
            'nip' => 'nullable|numeric|digits:18',
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'gender' => 'required',
            'birthplace' => 'required',
            'birthdate' => 'required'
        ]);

        try {
            $username = $request->post('nip') ?? strtolower(str_replace(' ', '', $request->post('name')));
            $data = [
                'nip' => $request->post('nip'),
                'name' => $request->post('name'),
                'username' => $username,
                'email' => $request->post('email'),
                'password' => $request->post('password'),
                'gender' => $request->post('gender'),
                'birthplace' => $request->post('birthplace'),
                'birthdate' => $request->post('birthdate')
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
        return redirect()->route('teacher.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $request = Http::withToken(session('token'))->get($this->endpoint . $id);
        $item = ($request->successful() && $request->json()['status'] == 200) ? $request->json()['data'] : [];
        if (!$item) {
            abort(404);
        }

        $req_day = Http::withToken(session('token'))->get($this->day);
        $req_time = Http::withToken(session('token'))->get($this->time);
        $req_schedule = Http::withToken(session('token'))->get($this->schedule . 'findByTeacher/' . $id);
        $data_schedule = ($req_schedule->successful() && $req_schedule->json()['status'] == 200) ? $req_schedule->json()['data'] : [];
        $schedule = [];
        foreach ($data_schedule as $ds) {
            $schedule[$ds['day_id']][$ds['time_id']] = [
                'id' => $ds['id'],
                'subject' => $ds['subject'],
                'grade' => $ds['grade'],
                'class' => $ds['class']
            ];
        }
        
        $data = [
            'title' => 'Dashboard',
            'day' => ($req_day->successful() && $req_day->json()['status'] == 200) ? $req_day->json()['data'] : [],
            'time' => ($req_time->successful() && $req_time->json()['status'] == 200) ? $req_time->json()['data'] : [],
            'schedule' => $schedule,
            'data' => $item
        ];

        return view('teacher.show', $data);
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

        $item = ($request->successful() && $request->json()['status'] == 200) ? $request->json()['data'] : [];
        if (!$item) {
            abort(404);
        }
        $data = [
            'title' => 'Teacher',
            'data' => $item
        ];

        return view('teacher.edit', $data);
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
            'nip' => 'nullable|numeric|digits:9',
            'name' => 'required',
            'gender' => 'required',
            'birthplace' => 'required',
            'birthdate' => 'required'
        ]);

        try {
            $username = $request->post('nip') ?? strtolower(str_replace(' ', '', $request->post('name')));
            $data = [
                'nip' => $request->post('nip'),
                'name' => $request->post('name'),
                'username' => $username,
                'gender' => $request->post('gender'),
                'birthplace' => $request->post('birthplace'),
                'birthdate' => $request->post('birthdate')
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
        return redirect()->route('teacher.index');
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
        return redirect()->route('teacher.index');
    }
}
