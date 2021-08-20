@extends('layout.app')

@section('content')
<div class="card">
    <div class="card-header">
        <h4>List of subject</h4>
    </div>
    <div class="card-body">
        {!! session('response') !!}
        <a href="{{ route('subject.create') }}" class="btn btn-primary mb-4">Create</a>
        <div class="table-responsive">
            <table class="table table-bordered table-hover datatable">
                <thead>
                    <tr>
                        {{-- <th class="text-center">
                            <div class="custom-checkbox custom-control">
                                <input type="checkbox" data-checkboxes="subject" data-checkbox-role="dad"
                                    class="custom-control-input" id="checkbox-all">
                                <label for="checkbox-all" class="custom-control-label">&nbsp;</label>
                            </div>
                        </th> --}}
                        <th>No</th>
                        <th>Kode</th>
                        <th>Mata Pelajaran</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data as $item)
                    <tr>
                        {{-- <td>
                            <div class="custom-checkbox custom-control">
                                <input type="checkbox" data-checkboxes="subject" class="custom-control-input"
                                    id="checkbox-{{ $loop->iteration }}" name="ids[]" value="{{ $item['id'] }}">
                                <label for="checkbox-{{ $loop->iteration }}" class="custom-control-label">&nbsp;</label>
                            </div>
                        </td> --}}
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $item['code'] }}</td>
                        <td>{{ $item['subject'] }}</td>
                        <td class="d-flex">
                            <a href="{{ route('subject.edit', $item['id']) }}" class="btn btn-primary mr-2"
                                title="Edit">
                                Edit
                            </a>
                            <form id="form_{{ $loop->iteration }}" action="{{ route('subject.destroy', $item['id']) }}"
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
