@extends('layout.app')

@section('content')
<div class="card">
    <div class="card-header">
        <h4>Create new data</h4>
    </div>
    <div class="card-body">
        <form class="form" method="POST" action="{{ route('teacher.store') }}">
            @csrf
            <div class="form-group">
                <label>NIP:</label>
                <input type="number" name="nip" class="form-control @error('nip') is-invalid @enderror"
                    placeholder="Masukkan NIP" value="{{ old('nip') }}" />
                @error('nip')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
                @enderror
            </div>
            <div class="form-group">
                <label>Nama:</label>
                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                    placeholder="Masukkan nama" value="{{ old('name') }}" required />
                @error('name')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
                @enderror
            </div>
            <div class="form-group">
                <label>Email:</label>
                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                    placeholder="Masukkan email" value="{{ old('email') }}" required />
                @error('email')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
                @enderror
            </div>
            <div class="form-group">
                <label>Password:</label>
                <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                    placeholder="Masukkan password" value="{{ old('password') }}" required />
                @error('password')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
                @enderror
            </div>
            <div class="form-group">
                <label>Jenis Kelamin:</label>
                <select name="gender" class="form-control select2 @error('gender') is-invalid @enderror" required>
                    <option value="1" @if(old('gender')==1) selected @endif>Laki-laki</option>
                    <option value="2" @if(old('gender')==2) selected @endif>Perempuan</option>
                </select>
                @error('gender')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
                @enderror
            </div>
            <div class="form-group">
                <label>Tempat lahir:</label>
                <input type="text" name="birthplace" class="form-control @error('birthplace') is-invalid @enderror"
                    placeholder="Masukkan tempat lahir" value="{{ old('birthplace') }}" required />
                @error('birthplace')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
                @enderror
            </div>
            <div class="form-group">
                <label>Tanggal lahir:</label>
                <input type="date" name="birthdate" class="form-control @error('birthdate') is-invalid @enderror"
                    placeholder="Masukkan tanggal lahir" value="{{ old('birthdate') }}" required />
                @error('birthdate')
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
