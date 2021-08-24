<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class SubjectmatterController extends Controller
{
    public function __construct()
    {
        $this->middleware('superadmin')->except('create', 'store', 'show');
        $this->middleware('superadminteacher')->only('create', 'store');

        $this->endpoint = config('app.api_url') . 'subjectMatters/';
        $this->classroom = config('app.api_url') . 'classrooms/';
        $this->class = config('app.api_url') . 'classes/';
        $this->student = config('app.api_url') . 'students/';
        $this->classwork = config('app.api_url') . 'classworks/';
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
    public function create($class, $id = null)
    {
        if (session('role_id') == 1) {
            $request = Http::withToken(session('token'))->get($this->classroom . $id);
            $data = ($request->successful() && $request->json()['status'] == 200) ? $request->json()['data'] : [];
            $req_class = Http::withToken(session('token'))->get($this->class . $class);
        } elseif (session('role_id') == 2) {
            $request = Http::withToken(session('token'))->get($this->classroom . $class);
            $data = ($request->successful() && $request->json()['status'] == 200) ? $request->json()['data'] : [];
            if (session('teacher_id') != $data['teacher_id']) {
                abort(403);
            }
            $req_class = Http::withToken(session('token'))->get($this->class . $data['class_id']);
        }

        $data = [
            'title' => 'Subject Matter',
            'class' => ($req_class->successful() && $req_class->json()['status'] == 200) ? $req_class->json()['data'] : [],
            'data' => $data
        ];

        return view('subjectmatter.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store($class, $id, Request $request)
    {
        $request->validate([
            'title' => 'required',
            'content' => 'required',
            'file_path' => 'required',
            'is_task' => 'required'
        ]);

        try {
            $data = [
                'classroom_id' => $id,
                'title' => $request->post('title'),
                'content' => $request->post('content'),
                'file_path' => $request->post('file_path'),
                'is_task' => $request->post('is_task')
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
        
        if (session('role_id') == 1) {
            return redirect()->route('class.classroom.show', [$class, $id]);
        } elseif (session('role_id') == 2) {
            return redirect()->route('subject.show', $id);
        } else {
            abort(404);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($class, $classroom, $id = null)
    {   
        if (in_array(session('role_id'), [2, 3])) {
            // adjustment for role student and teacher
            $id = $classroom;
        }

        $request = Http::withToken(session('token'))->get($this->endpoint . $id);
        $data = ($request->successful() && $request->json()['status'] == 200) ? $request->json()['data'] : [];

        $req_classrom = Http::withToken(session('token'))->get($this->classroom . $data['classroom_id']);
        $classroom = ($req_classrom->successful() && $req_classrom->json()['status'] == 200) ? $req_classrom->json()['data'] : [];
        
        $req_class = Http::withToken(session('token'))->get($this->class . $classroom['class_id']);
        $class = ($req_class->successful() && $req_class->json()['status'] == 200) ? $req_class->json()['data'] : [];
        
        if (session('role_id') == 3) {
            $student = [];
            
            $req_classwork = Http::withToken(session('token'))->get($this->classwork . 'findByStudentSubjectMatter/' . session('student_id') . '/' . $id);
            $classwork = ($req_classwork->successful() && $req_classwork->json()['status'] == 200) ? $req_classwork->json()['data'] : [];
            
            if (session('class_id') != $classroom['class_id']) {
                abort(403);
            }
        } else {
            if(session('role_id') == 2 && session('teacher_id') != $classroom['teacher_id']) {
                abort(403);
            }
            $req_student = Http::withToken(session('token'))->get($this->student . 'findByClass/' . $class['id']);
            $student = ($req_student->successful() && $req_student->json()['status'] == 200) ? $req_student->json()['data'] : [];
            $req_classwork = Http::withToken(session('token'))->get($this->classwork . 'findBySubjectMatter/' . $id);
            $data_classwork = ($req_classwork->successful() && $req_classwork->json()['status'] == 200) ? $req_classwork->json()['data'] : [];
            $classwork = [];
            foreach ($data_classwork as $dc) {
                $classwork[$dc['student_id']] = $dc['file_path'];
            }
        }

        $data = [
            'title' => 'Subject Matter',
            'class' => $class,
            'classroom' => $classroom,
            'classwork' => $classwork,
            'student' => $student,
            'data' => $data
        ];

        return view('subjectmatter.show', $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($class, $classroom, $id)
    {
        $request = Http::withToken(session('token'))->get($this->endpoint . $id);
        $classroom = Http::withToken(session('token'))->get($this->classroom . $classroom);
        $req_class = Http::withToken(session('token'))->get($this->class . $class);

        $data = [
            'title' => 'Subject Matter',
            'class' => ($req_class->successful() && $req_class->json()['status'] == 200) ? $req_class->json()['data'] : [],
            'classroom' => ($classroom->successful() && $classroom->json()['status'] == 200) ? $classroom->json()['data'] : [],
            'data' => ($request->successful() && $request->json()['status'] == 200) ? $request->json()['data'] : []
        ];

        return view('subjectmatter.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $class, $classroom, $id)
    {
        $request->validate([
            'title' => 'required',
            'content' => 'required',
            'file_path' => 'required',
            'is_task' => 'required'
        ]);

        try {
            $data = [
                'classroom_id' => $classroom,
                'title' => $request->post('title'),
                'content' => $request->post('content'),
                'file_path' => $request->post('file_path'),
                'is_task' => $request->post('is_task')
            ];
    
            $store = Http::withToken(session('token'))->asJson()
                            ->put($this->endpoint . $id, $data);
            if ($store->failed() || $store->json()['status'] != 200) {
                $message = ($store->json()) ? $store->json()['error'] : "Data gagal diubah";
                throw new Exception($message);
            }
            responseSuccess("Data berhasil diubah");
        } catch (\Throwable $th) {
            responseError($th->getMessage());
        }
        return redirect()->route('class.classroom.show', [$class, $classroom]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($class, $classroom, $id)
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
        return redirect()->route('class.classroom.show', [$class, $classroom]);
    }
}
