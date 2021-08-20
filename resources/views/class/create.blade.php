@extends('layout.app')

@section('content')
<div class="card">
    <div class="card-header">
        <h4>Create new data</h4>
    </div>
    <div class="card-body">
        <form class="form" method="POST" action="{{ route('class.store') }}">
            @csrf
            <div class="form-group">
                <label>Tingkat:</label>
                <select name="grade" class="form-control select2 @error('grade') is-invalid @enderror" required>
                    <option value="10" @if(old('grade')==10) selected @endif>10</option>
                    <option value="11" @if(old('grade')==11) selected @endif>11</option>
                    <option value="12" @if(old('grade')==12) selected @endif>12</option>
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
                    placeholder="Masukkan kelas" value="{{ old('class') }}" required />
                @error('class')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
                @enderror
            </div>
            <div class="form-group">
                <label>Jurusan:</label>
                <select name="major_id" class="form-control select2" required>
                    @foreach ($major as $m)
                    <option value="{{ $m['id'] }}" @if(old('major_id')==$m['id']) selected @endif>
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
