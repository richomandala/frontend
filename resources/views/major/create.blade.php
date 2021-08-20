@extends('layout.app')

@section('content')
<div class="card">
    <div class="card-header">
        <h4>Create new data</h4>
    </div>
    <div class="card-body">
        <form class="form" method="POST" action="{{ route('major.store') }}">
            @csrf
            <div class="form-group">
                <label>Kode:</label>
                <input type="text" name="code" class="form-control @error('code') is-invalid @enderror"
                    placeholder="Masukkan kode" value="{{ old('code') }}" required />
                @error('code')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
                @enderror
            </div>
            <div class="form-group">
                <label>Jurusan:</label>
                <input type="text" name="major" class="form-control @error('major') is-invalid @enderror"
                    placeholder="Masukkan jurusan" value="{{ old('major') }}" required />
                @error('major')
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
