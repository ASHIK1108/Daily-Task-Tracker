<?php
function readJSON($filename) {
    $path = __DIR__ . '/../data/' . $filename;
    if (!file_exists($path)) return [];
    return json_decode(file_get_contents($path), true);
}

function saveJSON($filename, $data) {
    $path = __DIR__ . '/../data/' . $filename;
    return file_put_contents($path, json_encode($data, JSON_PRETTY_PRINT));
}

function generateUserId() {
    return 'u' . uniqid();
}
function generateTaskId() {
    return 't' . uniqid();
}

function currentTimestamp() {
    return date("Y-m-d H:i:s");
}
