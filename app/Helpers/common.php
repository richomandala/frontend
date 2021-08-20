<?php

use Carbon\Carbon;

function showDate($date = null, $format = "l, d F Y")
{
    if (!$date) {
        return;
    }
    if ($date == 'now') {
        $date = now();
    }
    return Carbon::parse($date)->locale('id')->translatedFormat($format);
}

function showGender($gender) {
    $response = '';
    if ($gender == 1) {
        $response = "Laki-laki";
    } elseif ($gender == 2) {
        $response = "Perempuan";
    } else {
        $response = "Tidak teridentifikasi";
    }
    return $response;
}

function showFile($file = null) {
    if (!$file) {
        return;
    }
    $explode = explode('.', $file);
    $extension = strtolower($explode[count($explode) - 1]);
    $image = ['jpg', 'jpeg', 'png'];
    if (in_array($extension, $image)) {
        echo '<img class="image img-fluid" src="' . $file . '" />';
    } else {
        echo '<a class="btn btn-primary" href="' . $file . '" target="_BLANK">Lihat File</a>';
    }
}

function showContent($content = null)
{
    if (!$content) {
        return;
    }
    $explode = explode(' ', $content, 50);
    if (count($explode) > 40) {
        echo '<p class="text-justify">' . implode(' ', $explode) . ' ...</p>';
    } else {
        if (strlen($content) >= 250) {
            echo '<p class="text-justify">' . substr($content, 0, 200) . ' ...</p>';
        } else {
            echo '<p class="text-justify">' . $content . '</p>';
        }
    }
}