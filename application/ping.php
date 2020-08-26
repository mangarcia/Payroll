<?php
    require '../libs/jsonwrapper.php';
    if(isset($_POST["ip"]) and !empty($_POST["ip"])){
        $respuesta = array();
        exec("/bin/ping -c {$_POST["paquetes"]} 192.168.{$_POST["ip"]}", $respuesta);
        $res["objeto"] = $respuesta;
        echo json_encode($res);
    }
