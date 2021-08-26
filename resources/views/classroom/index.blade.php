@extends('layout.app')

@section('content')
<div id="main-card" class="card">
    <div class="card-header">
        <h4>Roomchat</h4>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-sm-12 col-lg-8">
                <div class="card chat-box" id="mychatbox" style="height: 80vh">
                    <div class="card-body chat-content">
                    </div>
                    <div class="card-footer chat-form">
                        <form id="chat-form">
                            @csrf
                            <input type="hidden" name="classroom_id" value="{{ $schedule['classroom_id'] }}">
                            <input type="text" name="chat" class="form-control" placeholder="Type a message">
                            <button class="btn btn-primary btn-submit">
                                <i class="far fa-paper-plane"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                {{ showFile($photo) }}
            </div>
        </div>
    </div>
</div>
@endsection
@section('js')
<script>
    let last_update = '{{ $schedule["time_start"] }}'

    $(document).ready(function () {
        getChat()
        setInterval(getChat(), 5000);
        $('html, body').animate({
            scrollTop: $("#main-card").offset().top
        }, 1000);
    })

    function getChat() {
        let url = '{{ url("getchat") . "/" . $schedule["classroom_id"] }}'
        if (last_update) {
            url += '/' + last_update
        }
        $.ajax({
            url: url,
            method: 'GET',
            dataType: 'JSON',
            success: function (response) {
                if (response.error == false) {
                    const chats = response.data
                    for (let i = 0; i < chats.length; i++) {
                        if ($('#chat_' + chats[i].id).length == 0) {
                            let type = 'text';
                            $.chatCtrl('#mychatbox', {
                                id: chats[i].id,
                                name: chats[i].name,
                                text: (chats[i].chat != undefined ? chats[i].chat : ''),
                                time: chats[i].time,
                                picture: 'https://ui-avatars.com/api/?name=' + chats[i].encode_name,
                                position: 'chat-' + chats[i].position,
                                type: type
                            });
                        }
                    }
                }
            },
            error: function (error) {
                console.log(error.statusText)
            },
            complete: function () {
                const date = new Date()
                last_update =
                    `${date.getHours()}:${date.getMinutes()}:${(date.getSeconds() > 9) ? date.getSeconds() : '0' + date.getSeconds()}`
                getChat()
            }
        })
    }

    $("#chat-form").submit(function () {
        var me = $(this);

        if (me.find('input[name=chat]').val().trim().length > 0) {
            $.ajax({
                url: '{{ url("postchat") }}',
                method: 'POST',
                data: $('#chat-form').serialize(),
                dataType: 'JSON',
                success: function (response) {
                    if (response.error) {
                        swal('Error', response.msg, 'error');
                    }
                },
                error: function (error) {
                    swal('Error', error.statusText, 'error');
                }
            })
            me.find('input[name=chat]').val('');
        }
        return false;
    });

</script>
@endsection
