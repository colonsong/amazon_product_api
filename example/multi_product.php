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
              'B00M55C0NS',
              'B00OGUATX8',
            ];
?>

<form class="form-horizontal" method="post" action="?example=multi_product">
    <table class="table table-hover">
        <tr>
            <th></th><th>image</th><th>ASIN</th><th>title</th><th>list price/price</th><th>debug</th>
        </tr>
        <?php 
        $item_c = 0;
        foreach($asin_array as $key => $asin):
        $result = $obj->getCartPage($asin,FALSE);
        if($result === FALSE)
        {
          continue;
        }
        //print_r($result);
        //print_r($result['item_xml']['Items']['Item']);
        ?>
        <input type="hidden" name="OfferListingId[]" value="<?php echo $result['item_xml']['Items']['Request']['ItemLookupRequest']['ItemId']; ?>"/>
        <input type="hidden" name="Quantity[]" value="1"/>
        <tr>
            <td><input type="checkbox" name="check<?php echo $item_c?>"> 買</td>
            <td><img src="<?php echo $result['item_xml']['Items']['Item']['MediumImage']['URL']; ?>" alt="..."></td>
            <td><?php echo $result['item_xml']['Items']['Request']['ItemLookupRequest']['ItemId']; ?></td>
            <td><a href="<?php echo $result['item_xml']['Items']['Item']['DetailPageURL']; ?>" target="_blank"><?php echo $result['item_xml']['Items']['Item']['ItemAttributes']['Title']; ?></a></td>
            <td><?php echo $result['item_xml']['Items']['Item']['ItemAttributes']['ListPrice']['CurrencyCode']; ?> <?php echo $result['item_xml']['Items']['Item']['ItemAttributes']['ListPrice']['FormattedPrice']; ?>
                /<?php echo $result['item_xml']['Items']['Item']['Offers']['Offer']['OfferListing']['Price']['CurrencyCode']; ?> <?php echo $result['item_xml']['Items']['Item']['Offers']['Offer']['OfferListing']['Price']['FormattedPrice']; ?></td>
            <td><!--/<?php echo $result['item_xml']['Items']['Item']['ItemAttributes']['PackageQuantity'] ?>/<?php echo $result['item_xml']['Items']['Item']['Offers']['TotalOffers']; ?>/<?php var_dump($result['item_xml']['Items']['Item']); ?>--></td>
        </tr>
        <?php 
        $item_c++;
        endforeach;
        ?>
    </table>
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
