<?php
  header("Content-Type: text/plain; charset=UTF-8");
  header("Access-Control-Allow-Origin: *");
  header("Access-Control-Allow-Methods: POST, GET");
  header("Access-Control-Allow-Headers: Content-Type");
  header("Access-Control-Max-Age: 86400");
  header("X-her0-Api-Type: plain");
  header("X-Powered-By: her0.api");
  ($api->getSearch()) ? $searchString = $api->getSearch() : $searchString = "none";
  ($api->getMoreItems()) ? $moreItemsString = $api->getMoreItems() : $moreItemsString = "0";
  if(is_array($items) && count($items) > 0) {
    foreach ($items as $item) {
      if ($api->getOption() != 'min') {
        echo substr($item[1],1,-1) . ' ' . substr($item[0],10) . "\n";
      }else{
        echo substr($item[1],1,-1) . ' ' . $item[3] . ' ' . $item[2] . "\n";
      }
    }
    echo $api->getFirstItemId() . ' ' . $api->getLastItemId() . ' ' . $api->getItemCount() . ' ' . $api->getDate() . ' ' . $api->getSort() . ' ' . $api->getOption() . ' ' . $searchString . ' ' . $moreItemsString . "\n";
  }else{
    echo '0 0 0 ' . $api->getDate() . ' ' . $api->getSort() . ' ' . $api->getOption() . ' ' . $searchString . ' ' . $moreItemsString  . "\n";
  }
?>
