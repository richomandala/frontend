@extends('layout.app')

@section('content')
<div class="card">
    <div class="card-header">
        <h4>Detail of class ({{ $class['grade'] . ' - ' . $class['class'] }})</h4>
    </div>
    <div class="card-body">
        {!! session('response') !!}
        <div class="row">
            <div class="col-md-12">
                <div class="section-title mt-0 mb-2">
                    {{ $data['name'] . ' - ' . $data['subject'] }}
                </div>
            </div>
            <div class="col-md-12">
                <a href="{{ route('class.classroom.subjectmatter.create', [$data['class_id'], $data['id']]) }}" class="btn btn-primary mb-4">Create</a>
            </div>
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
                                <th>Judul</th>
                                <th>Isi</th>
                                <th>File</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($subject_matter as $item)
                            <tr>
                                {{-- <td>
                                    <div class="custom-checkbox custom-control">
                                        <input type="checkbox" data-checkboxes="class" class="custom-control-input"
                                            id="checkbox-{{ $loop->iteration }}" name="ids[]" value="{{ $item['id'] }}">
                                        <label for="checkbox-{{ $loop->iteration }}" class="custom-control-label">&nbsp;</label>
                                    </div>
                                </td> --}}
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $item['title'] }}</td>
                                <td>{{ showContent($item['content']) }}</td>
                                <td>{{ showFile($item['file_path']) }}</td>
                                <td class="d-flex">
                                    <a href="{{ route('class.classroom.subjectmatter.show', [$data['class_id'], $data['id'], $item['id']]) }}" class="btn btn-success mr-2"
                                        title="Detail">
                                        Detail
                                    </a>
                                    <a href="{{ route('class.classroom.subjectmatter.edit', [$data['class_id'], $data['id'], $item['id']]) }}" class="btn btn-primary mr-2"
                                        title="Edit">
                                        Edit
                                    </a>
                                    <form id="form_{{ $loop->iteration }}" action="{{ route('class.classroom.subjectmatter.destroy', [$data['class_id'], $data['id'], $item['id']]) }}"
                                        method="post">
                                        @csrf @method('DELETE')
                                    </form>
                                    <button class="btn btn-danger" data-confirm="Hapus data!|Yakin menghapus data ini?"
                                        data-confirm-yes="document.getElementById('form_{{ $loop->iteration }}').submit()">Delete</button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
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
