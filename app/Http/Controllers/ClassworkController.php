<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class ClassworkController extends Controller
{
    public function __construct()
    {
        $this->endpoint = config('app.api_url') . 'classworks/';
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
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store($class, $classroom, $id, Request $request)
    {
        $request->validate([
            'file_path' => 'required'
        ]);

        try {
            $file = $request->file('file_path');
            $file_name = Storage::disk('public')->put('upload', $file);
            $data = [
                'student_id' => session('student_id'),
                'subject_matter_id' => $id,
                'file_path' => 'storage/' . $file_name
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
        return redirect()->route('class.classroom.subjectmatter.show', [$class, $classroom, $id]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
    public function destroy($id)
    {
        //
    }
}
