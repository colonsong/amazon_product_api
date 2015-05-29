<?php
if(isset($_POST['pasin_str'])){

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
    /*echo '<PRE>';
    print_r($result);
    echo '</PRE>';
  else:*/
	if(isset($result['Items'])){
          if(isset($result['Items']['Request']['Errors'])){
?>
	<div class="panel panel-danger">
      <div class="panel-heading">
        <h3 class="panel-title">錯誤訊息</h3>
      </div>
      <div class="panel-body">
          <PRE><?php echo print_r($result['Items']['Request'], TRUE); ?></PRE>
      </div>
    </div>
<?php
      }else{
?>
	<div class="panel panel-primary">
      <div class="panel-heading">
        <h3 class="panel-title">傳送參數</h3>
      </div>
      <div class="panel-body">
          <PRE><?php echo print_r($result['Items']['Request'], TRUE); ?></PRE>
      </div>
    </div>
    <div class="panel panel-primary">
      <div class="panel-heading">
        <h3 class="panel-title">回應</h3>
      </div>
      <div class="panel-body">
          <PRE><?php echo print_r($result['Items']['Item'], TRUE); ?></PRE>
      </div>
    </div>
<?php
          }
      }
    }else{
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
<?php
    }
?>
