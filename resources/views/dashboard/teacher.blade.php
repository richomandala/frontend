@extends('layout.app')

@section('content')
    <div class="card">
        <div class="card-body">
            <div class="col-md-12">
                <div class="section-title">
                    Jadwal
                </div>
            </div>
            <div class="col-md-12">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
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
                                            ({{ $schedule[$day[$x]['id']][$i['id']]['grade'] . ' - ' . $schedule[$day[$x]['id']][$i['id']]['class']}})
                                        @else
                                            <span class="btn btn-sm btn-danger" title="No schedule">-</span>
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
@endsection
@section('css')
<style>
    table tr td {
        text-align: center;
    }
</style>
@endsection
