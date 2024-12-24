<?php

header("Access-Control-Allow-Method: POST, GET");
header("Content-Type: application/json");
include "configs\config.php";

$c1 = new Config();
$c1->connect();

$arr = [];

switch ($_SERVER['REQUEST_METHOD']) {
    case 'POST':
        $name = $_POST['name'];
        $email = $_POST['email'];
        $phone = $_POST['phone'];

        if($name != '' && $name != null){
                if($email != '' && $email != null){
                    if($phone != '' && $phone != null){
                        $res = $c1->insertData($name, $email, $phone);
                    } else{
                        http_response_code(400);
                        $arr['error'] = "Phone number not passed!";
                        break;
                        }
            }  else{
                http_response_code(400);
                $arr['error'] = "Email address not passed!";
                break;
            }
        } else{
            http_response_code(400);
            $arr['error'] = "Name not passed!";
            break;
        }

        $res ? $arr['msg'] = "Insertion Completed!" :
            $arr['msg'] = "Failed to insert data!";

        break;

    case 'GET':
        $res = $c1->readData("users");
        $users = [];

        if ($res) {
            while ($data = mysqli_fetch_assoc($res)) {
                array_push($users, $data);
            }
            $arr['data'] = $users;
        } else {
            http_response_code(400);
            $arr['error'] = "Data not found!";
        }
        break;

    default:
        http_response_code(400);
        $arr['error'] = "Invalid request type!";
        break;
}

echo json_encode($arr);
