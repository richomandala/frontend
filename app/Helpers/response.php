<?php

function responseSuccess($message = "Success")
{
    $alert = '
    <div class="alert alert-success">
        '. $message .'
    </div>
    ';
    return session()->flash('response', $alert);
}

function responseError($message = "Error")
{
    $alert = '
    <div class="alert alert-danger">
        '. $message .'
    </div>
    ';
    return session()->flash('response', $alert);
}