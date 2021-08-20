<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ClassroomController extends Controller
{
    public function __construct()
    {
        $this->middleware('superadmin')->except('show');
        $this->middleware('superadminteacher')->only('show');

        $this->endpoint = config('app.api_url') . 'classrooms/';
        $this->class = config('app.api_url') . 'classes/';
        $this->teacher = config('app.api_url') . 'teachers/';
        $this->subject = config('app.api_url') . 'subjects/';
        $this->subject_matter = config('app.api_url') . 'subjectMatters/';
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id)
    {
        $req_class = Http::withToken(session('token'))->get($this->class . $id);
        $req_teacher = Http::withToken(session('token'))->get($this->teacher);
        $req_subject = Http::withToken(session('token'))->get($this->subject);

        $data = [
            'title' => 'Classroom',
            'teacher' => ($req_teacher->successful() && $req_teacher->json()['status'] == 200) ? $req_teacher->json()['data'] : [],
            'subject' => ($req_subject->successful() && $req_subject->json()['status'] == 200) ? $req_subject->json()['data'] : [],
            'data' => ($req_class->successful() && $req_class->json()['status'] == 200) ? $req_class->json()['data'] : []
        ];

        return view('classroom.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store($id, Request $request)
    {
        $request->validate([
            'teacher_id' => 'required',
            'subject_id' => 'required'
        ]);

        try {
            $data = [
                'class_id' => $id,
                'teacher_id' => strtoupper($request->post('teacher_id')),
                'subject_id' => $request->post('subject_id')
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
        return redirect()->route('class.show', $id);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($class, $id)
    {
        $request = Http::withToken(session('token'))->get($this->endpoint . $id);
        $req_class = Http::withToken(session('token'))->get($this->class . $class);
        $req_subject_matter = Http::withToken(session('token'))->get($this->subject_matter . 'findByClassroom/' . $id);

        $data = [
            'title' => 'Classroom',
            'class' => ($req_class->successful() && $req_class->json()['status'] == 200) ? $req_class->json()['data'] : [],
            'subject_matter' => ($req_subject_matter->successful() && $req_subject_matter->json()['status'] == 200) ? $req_subject_matter->json()['data'] : [],
            'data' => ($request->successful() && $request->json()['status'] == 200) ? $request->json()['data'] : []
        ];

        return view('classroom.show', $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($class, $id)
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
        return redirect()->route('class.show', $class);
    }
}
