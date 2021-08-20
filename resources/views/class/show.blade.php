@extends('layout.app')

@section('content')
<div class="card">
    <div class="card-header">
        <h4>Detail of class ({{ $data['grade'] . ' - ' . $data['class'] }})</h4>
    </div>
    <div class="card-body">
        {!! session('response') !!}
        <div class="row">
            <div class="col-md-12">
                <div class="section-title mt-0 mb-4 p-0">
                    Guru dan Mata Pelajaran
                </div>
            </div>
            <div class="col-md-12">
                <a href="{{ route('class.classroom.create', $data['id']) }}" class="btn btn-primary mb-4">Create</a>
            </div>
            <div class="col-md-12">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover datatable">
                        <thead>
                            <tr>
                                {{-- <th class="text-center">
                                    <div class="custom-checkbox custom-control">
                                        <input type="checkbox" data-checkboxes="class" data-checkbox-role="dad"
                                            class="custom-control-input" id="checkbox-all">
                                        <label for="checkbox-all" class="custom-control-label">&nbsp;</label>
                                    </div>
                                </th> --}}
                                <th>No</th>
                                <th>Guru</th>
                                <th>Mata Pelajaran</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($classroom as $item)
                            <tr>
                                {{-- <td>
                                    <div class="custom-checkbox custom-control">
                                        <input type="checkbox" data-checkboxes="class" class="custom-control-input"
                                            id="checkbox-{{ $loop->iteration }}" name="ids[]" value="{{ $item['id'] }}">
                                        <label for="checkbox-{{ $loop->iteration }}" class="custom-control-label">&nbsp;</label>
                                    </div>
                                </td> --}}
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $item['name'] }}</td>
                                <td>{{ $item['subject'] }}</td>
                                <td class="d-flex">
                                    <a href="{{ route('class.classroom.show', [$data['id'], $item['id']]) }}" class="btn btn-success mr-2"
                                        title="Detail">
                                        Detail
                                    </a>
                                    <a href="{{ route('class.classroom.edit', [$data['id'], $item['id']]) }}" class="btn btn-primary mr-2"
                                        title="Edit">
                                        Edit
                                    </a>
                                    <form id="form_{{ $loop->iteration }}" action="{{ route('class.classroom.destroy', [$data['id'], $item['id']]) }}"
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
        <div class="row mt-4">
            <div class="col-md-12">
                <div class="section-title">
                    Siswa
                </div>
            </div>
            <div class="col-md-12">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover datatable">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>NIS</th>
                                <th>Nama</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($student as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $item['nis'] }}</td>
                                <td>{{ $item['name'] }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div id="jadwal" class="row mt-4">
            <div class="col-md-12">
                <div class="section-title">
                    Jadwal
                </div>
            </div>
            <div class="col-md-12">
                <div class="table-responsive">
                    <table id="tbl-jadwal" class="table table-bordered table-hover">
                        <tr>
                            <th>&nbsp;</th>
                            @foreach ($day as $d)
                                <th>{{ $d['day'] }}</th>
                            @endforeach
                        </tr>
                        @foreach ($time as $i)
                            <tr>
                                <th width="15%">{{ showDate($i['time_start'], 'H.i') . ' - ' . showDate($i['time_end'], 'H.i') }}</th>
                                @php $cday = count($day); @endphp
                                @for ($x = 0; $x < $cday; $x++)
                                    <td>
                                        @if (isset($schedule[$day[$x]['id']][$i['id']]))
                                            {{ $schedule[$day[$x]['id']][$i['id']]['subject'] }} <br>
                                            ({{ $schedule[$day[$x]['id']][$i['id']]['teacher'] }})
                                            <form id="form_jadwal_{{ $schedule[$day[$x]['id']][$i['id']]['id'] }}" action="{{ route('schedule.destroy', $schedule[$day[$x]['id']][$i['id']]['id']) }}"
                                                method="post">
                                                @csrf @method('DELETE')
                                                <input type="hidden" name="class_id" value="{{ $data['id'] }}">
                                            </form>
                                            <button class="btn btn-sm btn-danger mb-2" data-confirm="Hapus data!|Yakin menghapus data ini?"
                                                data-confirm-yes="document.getElementById('form_jadwal_{{ $schedule[$day[$x]['id']][$i['id']]['id'] }}').submit()" title="Remove">x</button>
                                        @else
                                            <button data-day="{{ $day[$x]['id'] }}" data-time="{{ $i['id'] }}" class="btn btn-sm btn-success addschedule" title="Add">+</button>
                                        @endif
                                    </td>
                                @endfor
                            </tr>
                        @endforeach
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<form action="{{ route('schedule.store') }}" id="form-modal" method="post">
    @csrf
    <input type="hidden" name="class_id" value="{{ $data['id'] }}">
    <input type="hidden" name="day_id" id="day_id">
    <input type="hidden" name="time_id" id="time_id">
    <div class="form-group">
        <label>Mata pelajaran:</label>
        <select id="subject_id" name="subject_id" class="form-control" required>
            @foreach ($classroom as $cr)
            <option value="{{ $cr['subject_id'] }}" @if(old('subject_id')==$cr['subject_id']) selected @endif>
                {{ $cr['subject'] . ' - ' . $cr['name'] }}
            </option>
            @endforeach
        </select>
        @error('subject_id')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
        @enderror
    </div>
</form>
@endsection
@section('css')
<style>
    #tbl-jadwal tr td {
        text-align: center;
    }
    #modal {
        z-index: 9999999 !important;
    }
</style>
@endsection
@section('js')
<script>
    $('.addschedule').click(function(){
        $('#method').val('POST')
        $('#time_id').val($(this).data('time'))
        $('#day_id').val($(this).data('day'))
        $(this).fireModal({
            title: 'Add schedule',
            body: $("#form-modal"),
            footerClass: 'bg-whitesmoke',
            autoFocus: true,
            onFormSubmit: function(modal, e, form) {
                $('#form-modal').submit()
            },
            shown: function(modal, form) {
                // console.log(form)
            },
            buttons: [
                {
                text: 'Submit',
                submit: true,
                class: 'btn btn-primary btn-shadow',
                handler: function(modal) {
                }
                }
            ]
        });
    })
</script>
@endsection
