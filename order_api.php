<?php

header("Access-Control-Allow-Method: POST, GET, DELETE");
header("Content-Type: application/json");
include "configs\config.php";

$c1 = new Config();
$c1->connect();

$arr = [];

switch ($_SERVER['REQUEST_METHOD']) {
    case 'POST':
        $orderDate = $_POST['orderdate'];
        $status = $_POST['status'];

        if($orderDate != null && $orderDate != ''){
            if($status != null && $status != ''){
                $res = $c1->insertDataInOrders($orderDate, $status);
            } else{
                http_response_code(400);
                $arr['error'] = "Status not passed!";
            }
        } else{
            http_response_code(400);
            $arr['error'] = "Order is not passed";
        }

        $res ? $arr['msg'] = "Insertion Completed!" :
            $arr['msg'] = "Failed to insert data!";

        break;

    case 'GET':
        $res = $c1->readData("orders");
        $orders = [];

        if ($res) {
            while ($data = mysqli_fetch_assoc($res)) {
                array_push($orders, $data);
            }
            $arr['data'] = $orders;
        } else {
            $arr['error'] = "Data not found!";
        }
        break;

    case 'DELETE':
        $data = file_get_contents("php://input");
        parse_str($data, $result);

        $id = $result['id'];
        $res = $c1->deleteData($id);

        $res ?
            $arr['msg'] = "Data deleted successfully!" :
            $arr['msg'] = "Failed to delete data!";

        break;

    default:
        http_response_code(400);
        $arr['error'] = "Invalid request type!";
        break;
}

echo json_encode($arr);
