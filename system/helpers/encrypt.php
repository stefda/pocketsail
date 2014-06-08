<?php

function encid($id) {
    $code = '';
    $id = str_pad($id, 8, '0', STR_PAD_LEFT);
    for ($i = 0; $i < strlen($id); $i++) {
        $code .= substr($id, $i, 1);
        $cpos = rand(0, strlen(ENC_CYP) - 1);
        $char = substr(ENC_CYP, $cpos, 1);
        $code .= $char;
    }
    return base64_encode($code);
}

function decid($cyp) {
    $decoded = base64_decode($cyp);
    $decId = '';
    for ($i = 0; $i < strlen($decoded); $i++) {
        $decId .= substr($decoded, $i * 2, 1);
    }
    $decId = ltrim($decId, '0');
    return intval($decId);
}
