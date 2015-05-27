<?php
if(isset($_POST['asin_str']) ):

    if(empty(trim($_POST['asin_str'])))
    {
       return;
    }
    /* Example usage of the Amazon Product Advertising API */
    require("amazon_api_class.php");

    $obj = new AmazonProductAPI();

    try
    {
        /**
        http://www.amazon.com/Vans-AUTHENTIC-SKATE-SHOES-WHITE/dp/B000UYJBOW/ref=sr_1_6?s=sporting-goods&ie=UTF8&qid=1432261211&sr=1-6&asin_strs=shoes
        */
        $result = $obj->getItemByAsin($_POST['asin_str']);
    }
    catch(Exception $e)
    {
        echo $e->getMessage();
    }
    echo '<PRE>';
    print_r($result);
    echo '</PRE>';
else:
?>
<form class="form-horizontal" method="post" action="?example=item_by_asin">
  <div class="form-group">
    <label for="asin_str" class="col-sm-2 control-label">ASIN</label>
    <div class="col-sm-10">
      <input type="text" name="asin_str" class="form-control" id="asin_str" placeholder="ex : B000UYJBOW">
    </div>
  </div>

  <div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
      <button type="submit" class="btn btn-default">Submit</button>
    </div>
  </div>
</form>
<?php endif;?>
