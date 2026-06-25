<?php
include "db.php";
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");

$data = json_decode(file_get_contents('php://input'), true);

if ($data) {
    $mode = $data['mode'];
    $status = $data['status'];
    $durasi = $data['durasi'];

    $query = "UPDATE kontrol_pompa SET mode='$mode', status='$status', durasi='$durasi' WHERE id=1";
    if ($koneksi->query($query)) {
        echo json_encode(["status" => "success"]);
    } else {
        echo json_encode(["status" => "error", "message" => $koneksi->error]);
    }
}
?>