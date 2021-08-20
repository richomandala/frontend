<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->day = config('app.api_url') . 'days/';
        $this->time = config('app.api_url') . 'times/';
        $this->schedule = config('app.api_url') . 'schedules/';
    }

    public function index()
    {
        $role_id = session('role_id');
        if ($role_id == 1) {
            return view('dashboard.superadmin');
        }
        if ($role_id == 2) {
            $req_day = Http::withToken(session('token'))->get($this->day);
            $req_time = Http::withToken(session('token'))->get($this->time);
            $req_schedule = Http::withToken(session('token'))->get($this->schedule . 'findByTeacher/' . session('teacher_id'));
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
                'schedule' => $schedule
            ];

            return view('dashboard.teacher', $data);
        }
        if ($role_id == 3) {
            $req_day = Http::withToken(session('token'))->get($this->day);
            $req_time = Http::withToken(session('token'))->get($this->time);
            $req_schedule = Http::withToken(session('token'))->get($this->schedule . 'findByClass/' . session('class_id'));
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
                'title' => 'Dashboard',
                'day' => ($req_day->successful() && $req_day->json()['status'] == 200) ? $req_day->json()['data'] : [],
                'time' => ($req_time->successful() && $req_time->json()['status'] == 200) ? $req_time->json()['data'] : [],
                'schedule' => $schedule
            ];

            return view('dashboard.student', $data);
        }
    }
}
