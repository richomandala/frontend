@extends('layout.app')

@section('content')
<div class="card">
    <div class="card-header">
        <h4>Edit data</h4>
    </div>
    <div class="card-body">
        <form class="form" method="POST" action="{{ route('class.update', $data['id']) }}">
            @csrf @method('PUT')
            <div class="form-group">
                <label>Tingkat:</label>
                <select name="grade" class="form-control select2 @error('grade') is-invalid @enderror" required>
                    @php $grade = old('grade') ?? $data['grade']; @endphp
                    <option value="10" @if($grade==10) selected @endif>10</option>
                    <option value="11" @if($grade==11) selected @endif>11</option>
                    <option value="12" @if($grade==12) selected @endif>12</option>
                </select>
                @error('grade')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
                @enderror
            </div>
            <div class="form-group">
                <label>Kelas:</label>
                <input type="text" name="class" class="form-control @error('class') is-invalid @enderror"
                    placeholder="Masukkan kelas" value="{{ old('class') ?? $data['class'] }}" required />
                @error('class')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
                @enderror
            </div>
            <div class="form-group">
                <label>Jurusan:</label>
                @php $major_id = old('major_id') ?? $data['major_id']; @endphp
                <select name="major_id" class="form-control select2" required>
                    @foreach ($major as $m)
                    <option value="{{ $m['id'] }}" @if($major_id==$m['id']) selected @endif>
                        {{ $m['major'] }}
                    </option>
                    @endforeach
                </select>
                @error('major_id')
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
