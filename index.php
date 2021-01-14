<?php
$route = $_SERVER['REQUEST_URI'];
$method = $_SERVER['REQUEST_METHOD'];

$route = substr($route, 1);
$route = explode("?", $route);
$route = explode("/", $route[0]);
$route = array_diff($route, array('api'));
$route = array_values($route);

$arr_json = null;

if (count($route) <= 2) {

    switch ($route[0]) {
        case 'oracle':
            # code...
            include('API.php');
            if ($method == "GET")
                $_REQUEST['data'] = (isset($route[1]) ? $route[1] : $_REQUEST['data']);
            $client = new API($_REQUEST['data']);
            $arr_json = $client->reroute($method, $route);
            break;

        default:
            $arr_json = array('status' => 404);
            break;
    }

} else {
    $arr_json = array('status' => 404);
}

echo json_encode($arr_json);

?>