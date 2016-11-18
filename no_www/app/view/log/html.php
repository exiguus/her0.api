<?php
  header("Content-Type: text/html; charset=UTF-8");
  header("Access-Control-Allow-Origin: *");
  header("Access-Control-Allow-Methods: POST, GET");
  header("Access-Control-Allow-Headers: Content-Type");
  header("Access-Control-Max-Age: 86400");
  header("X-her0-Api-Type: html");
  header("X-Powered-By: her0.api");
  $time = time();
?>
<!-- START: new items <?php echo $time ?> -->
<?php
  if(is_array($items) && count($items) > 0) {
?>
<?php
    foreach ($items as $item) {
?>
<li id="item<?php echo htmlentities($item[4]); ?>">
  <time datetime="<?php echo htmlentities($api->getTimestampDate() . 'T' .substr($item[1],1,-1)); ?>">
    <?php echo htmlentities($item[1]); ?>
  </time>
  <?php if ($api->getOption() != 'min') { ?>
  <span>
    <?php echo htmlentities(substr($item[0],10)); ?>
  </span>
  <?php }else{ ?>
  <strong>
    <?php echo htmlentities($item[3]); ?>
  </strong>
  <em>
    <?php echo htmlentities($item[2]); ?>
  </em>
  <?php } ?>
</li>
<?php
    }
?>
<script type="text/javascript">
  var firstItemId = <?php echo $api->getFirstItemId() ?>;
  var lastItemId = <?php echo $api->getLastItemId() ?>;
  var itemCount =  <?php echo $api->getItemCount() ?>;
  var itemDate = <?php echo $api->getDate() ?>;
  var itemSort = "<?php echo $api->getSort() ?>";
  var option = "<?php echo $api->getOption() ?>";
  var search = "<?php echo $api->getSearch() ?>";
  var moreItems = <?php echo $api->getMoreItems() ?>;
</script>
<?php
  }else{
?>
<!--
      INFO:
      no new items

       ___  ___  _ __ _ __ _   _
      / __|/ _ \| '__| '__| | | |
      \__ \ (_) | |  | |  | |_| |
      |___/\___/|_|  |_|   \__, |
                           |___/

-->
<script type="text/javascript">
  var firstItemId = 0;
  var lastItemId = 0;
  var itemCount =  0;
  var itemDate = <?php echo $api->getDate() ?>;
  var itemSort = "<?php echo $api->getSort() ?>";
  var option = "<?php echo $api->getOption() ?>";
  var search = "<?php echo $api->getSearch() ?>";
  var moreItems = 0;
</script>
<?php
  }
?>
<!-- END: new items <?php echo $time ?> -->
