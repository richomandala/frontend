<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class MajorController extends Controller
{
    public function __construct()
    {
        $this->endpoint = config('app.api_url') . 'majors/';
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
            'title' => 'Major',
            'data' => ($request->successful() && $request->json()['status'] == 200) ? $request->json()['data'] : [],
        ];

        return view('major.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data = [
            'title' => 'Major'
        ];

        return view('major.create', $data);
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
            'major' => 'required'
        ]);

        try {
            $data = [
                'code' => strtoupper($request->post('code')),
                'major' => $request->post('major')
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
        return redirect()->route('major.index');
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
            'title' => 'Major',
            'data' => $item
        ];

        return view('major.edit', $data);
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
            'major' => 'required'
        ]);

        try {
            $data = [
                'code' => strtoupper($request->post('code')),
                'major' => $request->post('major')
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
        return redirect()->route('major.index');
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
        return redirect()->route('major.index');
    }
}
