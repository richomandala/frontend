@extends('layout.app')

@section('content')
<div class="card">
    <div class="card-header">
        <h4>Create new data ({{ $data['grade'] . ' - ' . $data['class'] }})</h4>
    </div>
    <div class="card-body">
        <form class="form" method="POST" action="{{ route('class.classroom.store', $data['id']) }}">
            @csrf
            <div class="form-group">
                <label>Guru:</label>
                <select name="teacher_id" class="form-control select2" required>
                    @foreach ($teacher as $t)
                    <option value="{{ $t['id'] }}" @if(old('teacher_id')==$t['id']) selected @endif>
                        {{ $t['name'] }}
                    </option>
                    @endforeach
                </select>
                @error('teacher_id')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
                @enderror
            </div>
            <div class="form-group">
                <label>Mata pelajaran:</label>
                <select name="subject_id" class="form-control select2" required>
                    @foreach ($subject as $s)
                    <option value="{{ $s['id'] }}" @if(old('subject_id')==$s['id']) selected @endif>
                        {{ $s['subject'] }}
                    </option>
                    @endforeach
                </select>
                @error('subject_id')
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
