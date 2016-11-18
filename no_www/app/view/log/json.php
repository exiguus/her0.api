<?php
  header("Content-Type: application/json; charset=UTF-8");
  header("Access-Control-Allow-Origin: *");
  header("Access-Control-Allow-Methods: POST, GET, OPTIONS"); // OPTIONS used by newer browser for Access-Control handling
  header("Access-Control-Allow-Headers: Content-Type");
  header("Access-Control-Max-Age: 86400");
  header("X-her0-Api-Type: json");
  header("X-Powered-By: her0.api");
  $result = array();
  if(is_array($items) && count($items) > 0) {
    foreach ($items as $item) {
      array_push($result,array( 'content' => $item[0], 'timestamp' => $api->getDate() . preg_replace('/\:/','',substr($item[1],1,-1)) , 'datetime' => $api->getTimestampDate() .'T'. substr($item[1],1,-1), 'action' => $item[2], 'nickname' => $item[3], 'id' => $item[4]) );
    }
    $result = (object) array( 'moreItems' => $api->getMoreItems(), 'search' => $api->getSearch(), 'startDate' => $api->getDateStart(), 'endDate' => $api->getDateEnd(), 'itemDate' => $api->getDate(), 'itemCount' => $api->getItemCount(), 'itemSort' => $api->getSort(), 'itemOption' => $api->getOption(), 'firstItemId' => $api->getFirstItemId(), 'lastItemId' => $api->getLastItemId(), 'items' => $result );
  }else{
    $result = (object) array( 'moreItems' => false, 'search' => $api->getSearch(), 'startDate' => $api->getDateStart(), 'endDate' => $api->getDateEnd(), 'itemDate' => $api->getDate(), 'itemCount' => $api->getItemCount(), 'itemSort' => $api->getSort(), 'itemOption' => $api->getOption(), 'firstItemId' => 0000, 'lastItemId' => 0000, 'items' => array(), 'error' => 'no results found' );
  }
  ($api->getCallback()) ? $result = $api->getCallback() . '(' . $api->jsonpp(json_encode($result)) . ');'  : $result = $api->jsonpp(json_encode($result));
  echo $result;
?>
