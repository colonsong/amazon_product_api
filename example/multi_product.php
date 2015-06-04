<form class="form-horizontal" method="post" action="?example=multi_product">
    <div class="form-group">
        <label for="asin_str" class="col-sm-2 control-label">ASIN</label>
        <div class="col-sm-10">
            <input type="text" name="asin_str" class="form-control" id="asin_str" placeholder="ex : B000UYJBOW" value="<?php echo (isset($_POST['asin_str']))?$_POST['asin_str']:''; ?>">
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
            <button type="submit" class="btn btn-default">Submit</button>
        </div>
    </div>
</form>
<?php
    if(!isset($_SESSION['asin_list'])){
        $asin_array = array();
        $_SESSION['asin_list'] = $asin_array;
    }else{
        $asin_array = $_SESSION['asin_list'];
    }
    require("amazon_api_class.php");
    $obj = new AmazonProductAPI();
    if(isset($_POST['asin_str']) ){
        try
        {
            $result = $obj->getItemByAsin($_POST['asin_str']);
        }
        catch(Exception $e)
        {
            echo $e->getMessage();
        }
        if(isset($result['Items']['Item'])){
            array_push($asin_array,$_POST['asin_str']);
            $_SESSION['asin_list'] = $asin_array;
        }else{
?>
            <div class="alert alert-danger" role="alert"><?php echo "ASIN:".$_POST['asin_str']."商品不存在"; ?></div>
<?php
        }
    }
    
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
    if(!isset($_SESSION['cart'])):
?>
    <h1>目前購物車是空的</h1>
<?php 
    endif;
?>
<?php
    if(isset($_SESSION['cart']))
    {
?>
<div class="alert alert-success" role="alert"><strong>購物車有<?php echo count($_SESSION['cart']['Cart']['CartItems']['CartItem']); ?>樣物品,<?php echo $_SESSION['cart']['Cart']['CartItems']['SubTotal']['CurrencyCode']; ?> <?php echo $_SESSION['cart']['Cart']['CartItems']['SubTotal']['FormattedPrice']; ?></strong>
    <a href="<?php echo $_SESSION['cart']['Cart']['PurchaseURL']; ?>" target="_blank">付款去</a></div>
<?php
      //echo 'Cart Session';
      //echo '<PRE>';
      //print_r($_SESSION['cart']);
      //echo '</PRE>';
    }
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
