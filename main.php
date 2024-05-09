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

                case 'logout': 
                    echo json_encode($auth->logout($d));
                break;

                // get
                case 'getPatient':
                    if ($auth->checkValidSignature($d->id, $d->token)) {
                        echo json_encode($get->getPatient($d->payload));
                    }else{
                        echo errMsg(401);
                    }
                break;

                case 'getDoctor':
                    if ($auth->checkValidSignature($d->id, $d->token)) {
                        echo json_encode($get->getDoctor($d));
                    }else{
                        echo errMsg(401);
                    }
                break;

                case 'getAllDoctor':
                    if ($auth->checkValidSignature($d->id, $d->token)) {
                        echo json_encode($get->getAllDoctor($d));
                    }else{
                        echo errMsg(401);
                    }
                break;

                case 'getSpecialties':
                    echo json_encode($get->getSpecialties($d));
                break;

                case 'getAllPatients':
                    if ($auth->checkValidSignature($d->id, $d->token)) {
                        echo json_encode($get->getAllPatients($d));
                    }else{
                        echo errMsg(401);
                    }
                break;

                   case 'getSession':
                    if ($auth->checkValidSignature($d->id, $d->token)) {
                        echo json_encode($get->getSession($d));
                    }else{
                        echo errMsg(401);
                    }
                break;

                // add
                case 'addSession':
                    echo json_encode($post->addSession($d));
                break;

                case 'addPatient':
                    echo json_encode($auth->addPatient($d));
                break;

                case 'addAdmin':
                    echo json_encode($auth->addAdmin($d));
                break;

                case 'addDoctor':
                    echo json_encode($auth->addDoctor($d));
                break;

                 case 'updateDoctor':
                    echo json_encode($post->updateDoctor($d));
                break;

            
                // change password
                // update 
                // deletedeleteAdmin
                case 'deleteDoctor':
                    echo json_encode($post->deleteDoctor($d));
                break;
        }
        break;

    case 'OPTIONS':
        return 200;
        break;

    default:
        echo errMsg(404);
        break;
}
