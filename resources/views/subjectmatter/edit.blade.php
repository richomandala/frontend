@extends('layout.app')

@section('content')
<div class="card">
    <div class="card-header">
        <h4>Edit data
            ({{ $class['grade'] . ' - ' . $class['class'] . ', ' . $classroom['name'] . ' - ' . $classroom['subject'] }})</h4>
    </div>
    <div class="card-body">
        <form class="form" method="POST"
            action="{{ route('class.classroom.subjectmatter.update', [$classroom['class_id'], $classroom['id'], $data['id']]) }}">
            @csrf @method('PUT')
            <div class="form-group">
                <label>Judul:</label>
                <input type="text" name="title" class="form-control @error('title') is-invalid @enderror"
                    placeholder="Masukkan judul" value="{{ old('title') ?? $data['title'] }}" required />
                @error('title')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
                @enderror
            </div>
            <div class="form-group">
                <label>Isi:</label>
                <input type="text" name="content" class="form-control @error('content') is-invalid @enderror"
                    placeholder="Masukkan isi" value="{{ old('content') ?? $data['content'] }}" required />
                @error('content')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
                @enderror
            </div>
            <div class="form-group">
                <label>File:</label>
                <input type="text" name="file_path" class="form-control @error('file_path') is-invalid @enderror"
                    placeholder="Masukkan file" value="{{ old('file_path') ?? $data['file_path'] }}" required />
                @error('file_path')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
                @enderror
            </div>
            <div class="form-group">
                <label>Tugas:</label>
                @php $is_task = old('is_task') ?? $data['is_task'];  @endphp
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="is_task" id="is_task1"
                        value="1" {{ ($is_task == 1) ? 'checked' : '' }}>
                    <label class="form-check-label" for="is_task1">
                        Ya
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="is_task" id="is_task2"
                        value="0" {{ ($is_task === 0) ? 'checked' : '' }}>
                    <label class="form-check-label" for="is_task2">
                        Tidak
                    </label>
                </div>
                @error('is_task')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
                @enderror
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>
</div>
@endsection
