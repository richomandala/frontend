<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ClassController extends Controller
{
    public function __construct()
    {
        $this->endpoint = config('app.api_url') . 'classes/';
        $this->major = config('app.api_url') . 'majors/';
        $this->classroom = config('app.api_url') . 'classrooms/';
        $this->student = config('app.api_url') . 'students/';
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
            'title' => 'Class',
            'data' => ($request->successful() && $request->json()['status'] == 200) ? $request->json()['data'] : [],
        ];

        return view('class.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $req_major = Http::withToken(session('token'))->get($this->major);

        $data = [
            'title' => 'Class',
            'major' => ($req_major->successful() && $req_major->json()['status'] == 200) ? $req_major->json()['data'] : [],
        ];

        return view('class.create', $data);
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
            'class' => 'required',
            'grade' => 'required',
            'major_id' => 'required'
        ]);

        try {
            $data = [
                'class' => $request->post('class'),
                'grade' => $request->post('grade'),
                'major_id' => $request->post('major_id')
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
        return redirect()->route('class.index');
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

        $req_classroom = Http::withToken(session('token'))->get($this->classroom . 'findByClass/' . $id);
        $req_student = Http::withToken(session('token'))->get($this->student . 'findByClass/' . $id);
        $req_day = Http::withToken(session('token'))->get($this->day);
        $req_time = Http::withToken(session('token'))->get($this->time);
        $req_schedule = Http::withToken(session('token'))->get($this->schedule . 'findByClass/' . $id);
        $data_schedule = ($req_schedule->successful() && $req_schedule->json()['status'] == 200) ? $req_schedule->json()['data'] : [];
        $schedule = [];
        foreach ($data_schedule as $ds) {
            $schedule[$ds['day_id']][$ds['time_id']] = [
                'id' => $ds['id'],
                'subject' => $ds['subject'],
                'teacher' => $ds['name']
            ];
        }
        
        $data = [
            'title' => 'Class',
            'classroom' => ($req_classroom->successful() && $req_classroom->json()['status'] == 200) ? $req_classroom->json()['data'] : [],
            'student' => ($req_student->successful() && $req_student->json()['status'] == 200) ? $req_student->json()['data'] : [],
            'day' => ($req_day->successful() && $req_day->json()['status'] == 200) ? $req_day->json()['data'] : [],
            'time' => ($req_time->successful() && $req_time->json()['status'] == 200) ? $req_time->json()['data'] : [],
            'schedule' => $schedule,
            'data' => $item
        ];

        return view('class.show', $data);
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
        $req_major = Http::withToken(session('token'))->get($this->major);

        $item = ($request->successful() && $request->json()['status'] == 200) ? $request->json()['data'] : [];
        if (!$item) {
            abort(404);
        }
        $data = [
            'title' => 'Class',
            'major' => ($req_major->successful() && $req_major->json()['status'] == 200) ? $req_major->json()['data'] : [],
            'data' => $item
        ];

        return view('class.edit', $data);
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
            'class' => 'required',
            'grade' => 'required',
            'major_id' => 'required'
        ]);

        try {
            $data = [
                'class' => $request->post('class'),
                'grade' => $request->post('grade'),
                'major_id' => $request->post('major_id')
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
        return redirect()->route('class.index');
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
        return redirect()->route('class.index');
    }
}
