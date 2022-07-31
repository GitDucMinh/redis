<?php

function sendMail($template, $to, $subject, $data) {
    \Mail::send($template, $data->toArray(), function($message) use ($to, $subject) {
        $message->subject($subject);
        $message->to($to);
    });
}