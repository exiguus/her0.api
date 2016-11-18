<?php if(basename(__FILE__) == basename($_SERVER['PHP_SELF'])){exit('Error: No direct access allowed');}

$api = new Log();
// controle
$json_params = file_get_contents("php://input");
if (strlen($json_params) > 0 && $api->isValidJson($json_params)) {
	  $jsond = json_decode($json_params);
}
// item (int)
if (isset($_GET['item'])) {
  $api->setItem($_GET['item']);
}elseif (isset($_POST['item'])) {
  $api->setItem($_POST['item']);
}elseif (isset($jsond->item)) {
  $api->setItem($jsond->item);
}

// count (int)
if (isset($_GET['count'])) {
  $api->setCount($_GET['count']);
}elseif (isset($_POST['count'])) {
  $api->setCount($_POST['count']);
}elseif (isset($jsond->count)) {
  $api->setCount($jsond->count);
}

// type (string)
if (isset($_GET['type'])) {
  $api->setType($_GET['type']);
}elseif (isset($_POST['type'])) {
  $api->setType($_POST['type']);
}elseif (isset($jsond->type)) {
  $api->setType( $jsond->type);
}

// option (string)
if (isset($_GET['option'])) {
  $api->setOption($_GET['option']);
}elseif (isset($_POST['option'])) {
  $api->setOption($_POST['option']);
}elseif (isset($jsond->option)) {
  $api->setOption( $jsond->option);
}

// sort (string);
if (isset($_GET['sort'])) {
  $api->setSort($_GET['sort']);
}elseif (isset($_POST['sort'])) {
  $api->setSort($_POST['sort']);
}elseif (isset($jsond->sort)) {
  $api->setSort( $jsond->sort);
}

if (isset($_GET['callback']) && $api->getType() == 'json') {
  $api->setCallback($_GET['callback']);
}elseif(isset($_POST['callback']) && $api->getType() == 'json') {
  $api->setCallback($_POST['callback']);
}elseif (isset($jsond->callback) && $api->getType() == 'json') {
  $api->setCallback($jsond->callback);
}

// date (int)
if (isset($_GET['date'])) {
  $api->setDate($_GET['date']);
}elseif (isset($_POST['date'])) {
  $api->setDate($_POST['date']);
}elseif (isset($jsond->date)) {
  $api->setDate( $jsond->date);
}

// search (string);
if (isset($_GET['search'])) {
  $api->setSearch($_GET['search']);
}elseif (isset($_POST['search'])) {
  $api->setSearch($_POST['search']);
}elseif (isset($jsond->search)) {
  $api->setSearch( $jsond->search);
}

$items = $api->getItems();
$lineCount = $api->getLineCount();

if(isset($_GET['debug']) && $_GET['debug'] === "true") {
	header("Content-Type: application/json; charset=UTF-8");

	echo 'callback(' . $api->jsonpp(json_encode($api->debug($api))) . ')';
	return false;
}

include(APP_PATH.'view/log/'.$api->getType().'.php');
?>
