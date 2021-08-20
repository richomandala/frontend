@extends('layout.app')

@section('content')
<div class="card">
    <div class="card-header">
        <h4>({{ $class['grade'] . ' - ' . $class['class'] . ', ' . $classroom['name'] . ' - ' . $classroom['subject'] }})</h4>
    </div>
    <div class="card-body">
        {!! session('response') !!}
        <div class="row">
            <div class="col-md-12">
                <div class="section-title mt-0 mb-2">
                    {{ $classroom['name'] . ' - ' . $classroom['subject'] . ' - ' . $data['title'] }}
                </div>
            </div>
            <div class="col-md-12">
                <p>
                    {{ $data['content'] }}
                </p>
                {{ showFile($data['content']) }}
            </div>
            @if ($data['is_task'] == 1)
                <div class="col-md-12 mt-5">
                    <div class="section-title mt-0 mb-2">
                        @if (in_array(session('role_id'), [1, 2]))
                            Tugas Kelas
                        @elseif (session('role_id') == 3)
                            Upload Tugas
                        @endif
                    </div>
                </div>
                @if (in_array(session('role_id'), [1, 2]))
                    <div class="col-md-12">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover datatable">
                                <thead>
                                    <tr>
                                        {{-- <th class="text-center">
                                            <div class="custom-checkbox custom-control">
                                                <input type="checkbox" data-checkboxes="class" data-checkbox-role="dad"
                                                    class="custom-control-input" id="checkbox-all">
                                                <label for="checkbox-all" class="custom-control-label">&nbsp;</label>
                                            </div>
                                        </th> --}}
                                        <th>No</th>
                                        <th>Siswa</th>
                                        <th>FIle</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($student as $item)
                                    <tr>
                                        {{-- <td>
                                            <div class="custom-checkbox custom-control">
                                                <input type="checkbox" data-checkboxes="class" class="custom-control-input"
                                                    id="checkbox-{{ $loop->iteration }}" name="ids[]" value="{{ $item['id'] }}">
                                                <label for="checkbox-{{ $loop->iteration }}" class="custom-control-label">&nbsp;</label>
                                            </div>
                                        </td> --}}
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $item['name'] }}</td>
                                        <td>{{ (isset($classwork[$item['id']])) ? showFile($classwork[$item['id']]) : '-' }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @elseif (session('role_id') == 3)
                    <div class="col-md-12">
                        @if (!$classwork)
                            <form action="{{ route('class.classroom.subjectmatter.classwork.store', [$classroom['class_id'], $classroom['id'], $data['id']]) }}" method="post">
                                @csrf
                                <div class="form-group">
                                    <label>File:</label>
                                    <input type="text" name="file_path" class="form-control @error('file_path') is-invalid @enderror"
                                        placeholder="Masukkan file" value="{{ old('file_path') }}" required />
                                    @error('file_path')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </form>
                        @else
                            <p>Anda sudah mengumpulkan tugas ini.</p>
                            {{ showFile($classwork['file_path']) }}
                        @endif
                    </div>
                @endif
            @endif
        </div>
    </div>
</div>
<!-- Modal-->
<div class="modal fade" id="modal" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form id="form-modal" method="post">
            @csrf @method('DELETE')
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Hapus Data!</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <div class="modal-body">
                    Yakin menghapus data ini?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light-primary font-weight-bold"
                        data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-danger font-weight-bold">Hapus</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
@section('js')
<script>
    function destroy(url) {
        $('#form-modal').attr('action', url)
        $('#modal').modal('show')
    }
</script>
@endsection
