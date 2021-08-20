<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class SubjectController extends Controller
{
    public function __construct()
    {
        $this->middleware('superadmin')->except('index', 'create', 'store', 'show');
        $this->middleware('superadminteacher')->only('create', 'store');
        
        $this->endpoint = config('app.api_url') . 'subjects/';
        $this->classroom = config('app.api_url') . 'classrooms/';
        $this->subject_matter = config('app.api_url') . 'subjectMatters/';
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $role = session('role_id');

        if ($role == 1) {
            $request = Http::withToken(session('token'))->get($this->endpoint);
            $view = 'subject.index.superadmin';
        } elseif ($role == 2) {
            $teacher_id = session('teacher_id');
            $request = Http::withToken(session('token'))->get($this->classroom . 'findByTeacher/' . $teacher_id);
            $view = 'subject.index.teacher';
        } elseif ($role == 3) {
            $class = session('class_id');
            $request = Http::withToken(session('token'))->get($this->classroom . 'findByClass/' . $class);
            $view = 'subject.index.student';
        }
        
        $data = [
            'title' => 'Subject',
            'data' => ($request->successful() && $request->json()['status'] == 200) ? $request->json()['data'] : [],
        ];
        
        return view($view, $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data = [
            'title' => 'Subject'
        ];

        if (session('role_id') == 1) {
            return view('subject.create.superadmin', $data);
        } elseif (session('role_id') == 2) {
            return view('subject.create.teacher', $data);
        }
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
            'code' => 'required',
            'subject' => 'required'
        ]);

        try {
            $data = [
                'code' => strtoupper($request->post('code')),
                'subject' => $request->post('subject')
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
        return redirect()->route('subject.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $request = Http::withToken(session('token'))->get($this->classroom . $id);
        $req_subject_matter = Http::withToken(session('token'))->get($this->subject_matter . 'findByClassroom/' . $id);

        $item = ($request->successful() && $request->json()['status'] == 200) ? $request->json()['data'] : [];
        if (!$item) {
            abort(404);
        } elseif (session('role_id') == 2 && session('teacher_id') != $item['teacher_id']) {
            abort(403);
        } elseif (session('role_id') == 3 && session('class_id') != $item['class_id']) {
            abort(403);
        }
        $data = [
            'title' => 'Class',
            'subject_matter' => ($req_subject_matter->successful() && $req_subject_matter->json()['status'] == 200) ? $req_subject_matter->json()['data'] : [],
            'data' => $item
        ];

        return view('subject.show', $data);
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
            'title' => 'Subject',
            'data' => $item
        ];

        return view('subject.edit', $data);
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
            'code' => 'required',
            'subject' => 'required'
        ]);

        try {
            $data = [
                'code' => strtoupper($request->post('code')),
                'subject' => $request->post('subject')
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
        return redirect()->route('subject.index');
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
        return redirect()->route('subject.index');
    }
}
