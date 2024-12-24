<?php

header("Access-Control-Allow-Method: POST, GET, PUT");
header("Content-Type: application/json");
include "configs\config.php";

$c1 = new Config();
$c1->connect();

$arr = [];

switch ($_SERVER['REQUEST_METHOD']) {
    case 'POST':
        $productName = $_POST['productName'];
        $price = $_POST['price'];

        if($productName != '' && $productName != null){
            if($price != '' && $price != null){    
                $res = $c1->insertDataInProducts($productName, $price);
            } else{
                http_response_code(400);
                $arr['error'] = "Price not passed!";
            }
        } else{
            http_response_code(400);
            $arr['error'] = "Product Name not passed!";
        }

        $res ? $arr['msg'] = "Insertion Completed!" :
            $arr['msg'] = "Failed to insert data!";

        break;

    case 'GET':
        $res = $c1->readData("product");
        $products = [];

        if ($res) {
            while ($data = mysqli_fetch_assoc($res)) {
                array_push($products, $data);
            }
            $arr['data'] = $products;
        } else {
            $arr['error'] = "Data not found!";
        }
        break;

    case 'PUT':
        $data = file_get_contents("php://input");
        parse_str($data, $result);

        $id = $result['id'];
        $productName = $result['productName'];
        $price = $result['price'];

        if($id != null && $id != ''){
            if($productName != null && $productName != ''){
                if($price != null && $price != '') {
                    $res = $c1->updateData($id, $productName, $price);
                } else{
                    http_response_code(400);
                    $arr['error'] = "Price not passed!";
                }
            } else{
                http_response_code(400);
                $arr['error'] = "Product name not passed!";
            }
        } else{
            http_response_code(400);
            $arr['error'] = "Id not passed!";
        }

        $res ?
            $arr['msg'] = "Updated data!" :
            $arr['msg'] = "Failed to update data!";

        break;

    default:
        http_response_code(400);
        $arr['error'] = "Invalid request type!";
        break;
}

echo json_encode($arr);
