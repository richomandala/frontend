@extends('layout.app')

@section('content')
<div class="card">
    <div class="card-header">
        <h4>{{ $data['subject'] . ' - ' . $data['name'] . ' - ' .  $data['grade'] . ' ' . $data['class'] }}</h4>
    </div>
    <div class="card-body">
        <div class="row">
            @if (session('role_id') == 2)
                {!! session('response') !!}
                <a href="{{ route('subject.subjectmatter.create', $data['id']) }}" class="btn btn-primary mb-4">Create</a>
            @endif
            <div class="table-responsive">
                <table class="table table-bordered table-hover datatable">
                    <thead>
                        <tr>
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
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $item['title'] }}</td>
                            <td>{{ showContent($item['content']) }}</td>
                            <td>{{ showFile(asset($item['file_path'])) }}</td>
                            <td class="d-flex">
                                <a href="{{ route('subject.subjectmatter.show', [$data['id'], $item['id']]) }}" class="btn btn-success mr-2"
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
</div>
@endsection
