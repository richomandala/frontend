@extends('layout.app')

@section('content')
<div class="card">
    <div class="card-header">
        <h4>Edit data</h4>
    </div>
    <div class="card-body">
        <form class="form" method="POST" action="{{ route('student.update', $data['id']) }}">
            @csrf @method('PUT')
            <div class="form-group">
                <label>NISN:</label>
                <input type="number" name="nisn" class="form-control @error('nisn') is-invalid @enderror"
                    placeholder="Masukkan NISN" value="{{ old('nisn') ?? $data['nisn'] }}" required />
                @error('nisn')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
                @enderror
            </div>
            <div class="form-group">
                <label>NIS:</label>
                <input type="number" name="nis" class="form-control @error('nis') is-invalid @enderror"
                    placeholder="Masukkan NIS" value="{{ old('nis') ?? $data['nis'] }}" required />
                @error('nis')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
                @enderror
            </div>
            <div class="form-group">
                <label>Nama:</label>
                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                    placeholder="Masukkan nama" value="{{ old('name') ?? $data['name'] }}" required />
                @error('name')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
                @enderror
            </div>
            {{-- <div class="form-group">
                <label>Email:</label>
                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                    placeholder="Masukkan email" value="{{ old('email') ?? $data['email'] }}" required />
                @error('email')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
                @enderror
            </div>
            <div class="form-group">
                <label>Password:</label>
                <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                    placeholder="Kosongkan jika tidak ingin merubah password" />
                @error('password')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
                @enderror
            </div> --}}
            <div class="form-group">
                <label>Jenis Kelamin:</label>
                <select name="gender" class="form-control select2 @error('gender') is-invalid @enderror" required>
                    @php $gender = old('gender') ?? $data['gender']; @endphp
                    <option value="1" @if($gender==1) selected @endif>Laki-laki</option>
                    <option value="2" @if($gender==2) selected @endif>Perempuan</option>
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
                    placeholder="Masukkan tempat lahir" value="{{ old('birthplace') ?? $data['birthplace'] }}" required />
                @error('birthplace')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
                @enderror
            </div>
            <div class="form-group">
                <label>Tanggal lahir:</label>
                <input type="date" name="birthdate" class="form-control @error('birthdate') is-invalid @enderror"
                    placeholder="Masukkan tanggal lahir" value="{{ old('birthdate') ?? showDate($data['birthdate'], 'Y-m-d') }}" required />
                @error('birthdate')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
                @enderror
            </div>
            <div class="form-group">
                <label>Kelas:</label>
                @php $class_id = old('class_id') ?? $data['class_id']; @endphp
                <select name="class_id" class="form-control select2" required>
                    @foreach ($class as $c)
                    <option value="{{ $c['id'] }}" @if($class_id==$c['id']) selected @endif>
                        {{ $c['grade'] . ' - ' . $c['class'] }}
                    </option>
                    @endforeach
                </select>
                @error('class_id')
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
