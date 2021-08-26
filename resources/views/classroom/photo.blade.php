@extends('layout.app')

@section('content')
<div id="main-card" class="card">
    <div class="card-header">
        <h4>Roomchat</h4>
    </div>
    <div class="card-body">
        <div class="empty-state" data-height="400">
            <div class="empty-state-icon bg-info">
                <i class="fas fa-camera"></i>
            </div>
            <h2>Take a photo</h2>
            <p class="lead">
                <div id="camera">
                    <div id="my_camera" class="mb-2"></div>
                    <input type=button value="Take Snapshot" onClick="take_snapshot()">
                </div>
                <div id="preview" class="d-none">
                    <div id="results" class="mb-2"></div>
                    <form action="{{ url('postpresence/' . $schedule['id']) }}" method="post">
                        @csrf
                        <input type="hidden" name="photo" id="photo">
                        <button class="btn btn-primary btn-sm" type="submit">Submit</button>
                        <button class="btn btn-danger btn-sm" id="reset" type="button">Reset</button>
                    </form>
                </div>
                <br>
            </p>
        </div>
    </div>
</div>
@endsection
@section('css')
<style>
    #my_camera {
        width: 320px;
        height: 240px;
        border: 1px solid black;
    }

</style>
@endsection
@section('js')
<script src="{{ asset('assets/plugins/webcamjs/webcam.min.js') }}"></script>
<script>
    $(document).ready(function(){
        setWebcam()
    })

    function setWebcam() {
        Webcam.set({
            width: 320,
            height: 240,
            image_format: 'jpeg',
            jpeg_quality: 90
        });
        Webcam.attach('#my_camera');
    }


    // preload shutter audio clip
    var shutter = new Audio();
    shutter.autoplay = false;
    shutter.src = navigator.userAgent.match(/Firefox/) ? 'shutter.ogg' : 'shutter.mp3';

    function take_snapshot() {
        // play sound effect
        shutter.play();

        // take snapshot and get image data
        Webcam.snap(function (data_uri) {
            // display results in page
            document.getElementById('results').innerHTML =
                '<img id="imageprev" src="' + data_uri + '"/>';
            $('#photo').val(btoa(data_uri))
        });

        Webcam.reset();

        $('#camera').addClass('d-none')
        $('#preview').removeClass('d-none')
    }

    $('#reset').click(function(){
        $('#camera').removeClass('d-none')
        $('#preview').addClass('d-none')
        setWebcam()
    })

</script>
@endsection
