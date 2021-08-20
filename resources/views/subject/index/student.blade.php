@extends('layout.app')

@section('content')
<div class="card">
    <div class="card-header">
        <h4>List of subject</h4>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover datatable">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Mata Pelajaran</th>
                        <th>Guru</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data as $item)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $item['subject'] }}</td>
                        <td>{{ $item['name'] }}</td>
                        <td class="d-flex">
                            <a href="{{ route('subject.show', $item['id']) }}" class="btn btn-success mr-2"
                                title="Detail">
                                Detail
                            </a>
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
