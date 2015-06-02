<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require("amazon_api_class.php");
//unset($_SESSION['cart']);
//print_r($_SESSION['cart']);
//print_r($_POST);
$obj = new AmazonProductAPI();


  //send controll
  if(isset($_POST['OfferListingId']) && ! isset($_SESSION['cart']))
  {
     $cart = $obj->create_cart();


  }

  if(isset($_POST['clear']))
  {
    $cart = $obj->clear_cart();
    $obj->_pre($cart);
    unset($_SESSION['cart']);
  }
?>


<?php
  //unset($_SESSION['cart']);
  if(!isset($_SESSION['cart'])):?>
  <h1>目前購物車是空的</h1>
<?php endif;?>


<?php

//多筆商品
$asin_array = [
              'B003BYRGLI',
              'B00OGTS5ZS',
              'B00HQWZ6UY',
              'B00IB1BTWI',
              'B009NOGSQE',
              'B00PCLLXYY'
            ];
?>


<form class="form-horizontal" method="post" action="?example=multi_product">
  <div class="row">
  <?php foreach($asin_array as $key => $asin):
    $result = $obj->getCartPage($asin,FALSE);
    if($result === FALSE)
    {
      continue;
    }
    //print_r($result);
    //print_r($result['item_xml']['Items']['Item']);
    ?>


        <div class="col-sm-3 col-md-2">
          <div class="thumbnail">
            <img src="<?php echo $result['item_xml']['Items']['Item']['MediumImage']['URL']; ?>" alt="...">
            <div class="caption">
              <!--<h3><?php echo $result['item_xml']['Items']['Item']['ItemAttributes']['Title']; ?></h3>
              <p>item type:<?php echo $result['item_xml']['Items']['Request']['ItemLookupRequest']['IdType']; ?></p>  -->
              <p><?php echo $result['item_xml']['Items']['Request']['ItemLookupRequest']['ItemId']; ?></p>

              <input type="hidden" name="OfferListingId[]" value="<?php echo $result['item_xml']['Items']['Request']['ItemLookupRequest']['ItemId']; ?>"/>
              <input type="hidden" name="Quantity[]" value="1"/>
              </p>
              <!--
                <a href="#" class="btn btn-primary" role="button"><?php echo $result['item_xml']['Items']['Item']['ItemAttributes']['PackageQuantity'] ?></a>
                <a href="#" class="btn btn-primary" role="button"><?php echo $result['item_xml']['Items']['Item']['Offers']['TotalOffers']; ?></a>

              </p>
              -->
              <div class="checkbox">
                <label>
                  <input type="checkbox" name="check<?php echo $key?>"> 買
                </label>
              </div>
            </div>
          </div>
        </div>


<?php endforeach;?>
  </div>
  <button type="submit" class="btn btn-default">加入購物車</button>
</form>

<p>

<form class="form-horizontal" method="post" action="?example=multi_product">
  <input type="hidden" name="clear" value="1" />
  <button type="submit" class="btn btn-default">清空購物車</button>
</from>



<?php
if(isset($_SESSION['cart']))
{
  echo 'Cart Session';
  echo '<PRE>';
  print_r($_SESSION['cart']);
  echo '</PRE>';
}

?>
