<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ClassroomController extends Controller
{
    public function __construct()
    {
        $this->middleware('superadmin')->except('index', 'show', 'getChat', 'postChat');
        $this->middleware('superadminteacher')->only('show');

        $this->endpoint = config('app.api_url') . 'classrooms/';
        $this->class = config('app.api_url') . 'classes/';
        $this->teacher = config('app.api_url') . 'teachers/';
        $this->subject = config('app.api_url') . 'subjects/';
        $this->subject_matter = config('app.api_url') . 'subjectMatters/';
        $this->schedule = config('app.api_url') . 'schedules/';
        $this->roomchat = config('app.api_url') . 'roomchats/';
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        date_default_timezone_set("Asia/Jakarta");
        $class = session('class_id');
        $user = session('user_id');
        $req_schedule = Http::withToken(session('token'))->get($this->schedule . 'getScheduleClass/' . $class);
        $schedule = ($req_schedule->successful() && $req_schedule->json()['status'] == 200) ? $req_schedule->json()['data'] : [];
        if (!$schedule) {
            return view('classroom.no-schedule');
        }
        $data = [
            'title' => 'Roomchats',
            'user_id' => $user,
            'class_id' => $class,
            'schedule' => $schedule
        ];

        return view('classroom.index', $data);
    }

    public function getChat($classroom, $time = null)
    {
        if ($time) {
            $req_chat = Http::withToken(session('token'))->get($this->roomchat . $classroom . '/' . $time);
        } else {
            $req_chat = Http::withToken(session('token'))->get($this->roomchat . $classroom);
        }
        $chat = ($req_chat->successful() && $req_chat->json()['status'] == 200) ? $req_chat->json()['data'] : [];
        $error = true;
        $data = [];
        if ($chat) {
            $error = false;
            foreach ($chat as $c) {
                $data[] = [
                    'id' => $c['id'],
                    'position' => ($c['user_id'] == session('user_id')) ? 'right' : 'left',
                    'name' => $c['name'],
                    'encode_name' => urlencode($c['name']),
                    'chat' => $c['chat'],
                    'time' => $c['time']
                ];
            }
        }

        $result = [
            'error' => $error,
            'data' => $data
        ];
        echo json_encode($result);
    }

    public function postChat(Request $request)
    {
        $data = [
            'name' => session('name'),
            'chat' => $request->post('chat'),
            'is_teacher' => (session('teacher_id')) ? 1 : 0,
            'classroom_id' => $request->post('classroom_id'),
            'user_id' => session('user_id')
        ];
        
        $store = Http::withToken(session('token'))->asJson()
                            ->post($this->roomchat, $data);
        if ($store->failed() || $store->json()['status'] != 200) {
            $message = ($store->json()) ? $store->json()['error'] : "Pesan tidak terkirim";
            echo json_encode([
                'error' => true,
                'msg' => $message
            ]);
        } else {
            echo json_encode(['error' => false]);
        }
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
