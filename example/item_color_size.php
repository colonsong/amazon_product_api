<?php
if(isset($_POST['pasin_str']) ):

    /* Example usage of the Amazon Product Advertising API */
    require("amazon_api_class.php");

    $obj = new AmazonProductAPI();

    try
    {
        /**
        http://www.amazon.com/Vans-AUTHENTIC-SKATE-SHOES-WHITE/dp/B000UYJBOW/ref=sr_1_6?s=sporting-goods&ie=UTF8&qid=1432261211&sr=1-6&keywords=shoes
        */
        $result = $obj->getItemColorSize($_POST['pasin_str']);
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

<form class="form-horizontal" method="post" action="?example=item_color_size">
  <div class="form-group">
    <label for="pasin_str" class="col-sm-2 control-label">PARENT ASIN</label>
    <div class="col-sm-10">
      <input type="text" name="pasin_str" class="form-control" id="pasin_str" placeholder="ex : B001CW0A5K">
    </div>
  </div>

  <div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
      <button type="submit" class="btn btn-default">Submit</button>
    </div>
  </div>
</form>
<?php endif;?>
