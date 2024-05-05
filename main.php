<?php

require_once("./config/Config.php");
require_once("./modules/Procedural.php");
require_once("./modules/Global.php");
require_once("./modules/Auth.php");
require_once("./modules/Get.php");
require_once("./modules/Post.php");

$db = new Connection();
$pdo = $db->connect();
$gm = new GlobalMethods($pdo);
$auth = new Auth($pdo);
$get = new Get($pdo);
$post = new Post($pdo);

if (isset($_REQUEST['request'])) {
    $req = explode('/', rtrim($_REQUEST['request'], '/'));
} else {
    $req = array("errorcatcher");
}

switch ($_SERVER['REQUEST_METHOD']) {
    case 'POST':

        $d = json_decode(file_get_contents("php://input"));

        switch ($req[0]) {
                // login 
                case 'login':
                echo json_encode($auth->login($d));
                break;
                // get
                case 'admin':
                echo json_encode($get->getAdmin($d));
                break;
                // add
                // change password
                // update 
                // delete
        }
        break;

    case 'OPTIONS':
        return 200;
        break;

    default:
        echo errMsg(404);
        break;
}
