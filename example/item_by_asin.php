<form class="form-horizontal" method="post" action="?example=item_by_asin">
    <div class="form-group">
        <label for="asin_str" class="col-sm-2 control-label">ASIN</label>
        <div class="col-sm-10">
            <input type="text" name="asin_str" class="form-control" id="asin_str" placeholder="ex : B000UYJBOW" value="<?php echo (isset($_POST['asin_str']))?$_POST['asin_str']:''; ?>">
        </div>
    </div>
    <div class="form-group">
        <label for="category" class="col-sm-2 control-label">除錯</label>
        <div class="col-sm-10">
          <div class="checkbox">
            <label>
              <input type="checkbox" name="debug" value="1"<?php echo (isset($_POST['debug']) && $_POST['debug'] === '1')?' checked':''; ?>> Debug Mode
            </label>
          </div>
        </div>
    </div>  
    <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
            <button type="submit" class="btn btn-default">Submit</button>
        </div>
    </div>
</form>
<?php
    if(isset($_POST['asin_str']) ){
        require("amazon_api_class.php");

        $obj = new AmazonProductAPI();

        try
        {
            $result = $obj->getItemByAsin($_POST['asin_str']);
        }
        catch(Exception $e)
        {
            echo $e->getMessage();
        }
	
        if(isset($result['Items'])){
            if(isset($_POST['debug']) && $_POST['debug'] === '1'){
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
        }
        if(isset($result['Items']['Item'])):
            $result = $result['Items']['Item'];
?>
<div class="col-sm-12">
    <div class="panel panel-default">
        <div class="panel-heading">
          <h3 class="panel-title"><?php echo $result['ASIN']; ?> / <?php echo $result['ItemAttributes']['Title']; ?></h3>
        </div>
        <div class="panel-body">
            <form class="form-horizontal" method="post" action="?example=item_by_asin">
                <div class="form-group">
                    <label for="asin_str" class="col-sm-2 control-label">ASIN</label>
                    <div class="col-sm-10">
                        <?php echo $result['ASIN']; ?>
                    </div>
                </div>
                <div class="form-group">
                    <label for="asin_str" class="col-sm-2 control-label">Title</label>
                    <div class="col-sm-10">
                        <a href="<?php echo $result['DetailPageURL']; ?>" target="_blank"><?php echo $result['ItemAttributes']['Title']; ?></a>
                    </div>
                </div>
                <div class="form-group">
                    <label for="asin_str" class="col-sm-2 control-label">image</label>
                    <div class="col-sm-2">
                        <div class="thumbnail">
                            <img src="<?php echo $result['SmallImage']['URL']; ?>" alt="SmallImage 75x75">
                            <div class="caption">
                                <p>SmallImage 75x75</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="thumbnail">
                            <img src="<?php echo $result['MediumImage']['URL']; ?>" alt="MediumImage 160x160">
                            <div class="caption">
                                <p>MediumImage 160x160</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-5">
                        <div class="thumbnail">
                            <img src="<?php echo $result['LargeImage']['URL']; ?>" alt="LargeImage 300x300">
                            <div class="caption">
                                <p>LargeImage 300x300</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="asin_str" class="col-sm-2 control-label">Feature</label>
                    <div class="col-sm-10">
                        <ul class="list-group">
<?php
                        if(is_array($result['ItemAttributes']['Feature'])):
                            foreach($result['ItemAttributes']['Feature'] as $feature):
?>
                            <li class="list-group-item"><?php echo $feature; ?></li>
<?php
                            endforeach;
                        endif;
?>
                          </ul>
                    </div>
                </div>
                <div class="form-group">
                    <label for="asin_str" class="col-sm-2 control-label">Brand</label>
                    <div class="col-sm-10">
                        <?php echo $result['ItemAttributes']['Brand']; ?>
                    </div>
                </div>
<?php
                if($result['Offers']['TotalOffers'] > 0):
?>
                <div class="form-group">
                    <label for="asin_str" class="col-sm-2 control-label">ListPrice</label>
                    <div class="col-sm-10">
                        <?php echo $result['ItemAttributes']['ListPrice']['CurrencyCode']; ?> <?php echo $result['ItemAttributes']['ListPrice']['FormattedPrice']; ?>
                    </div>
                </div>
                <div class="form-group">
                    <label for="asin_str" class="col-sm-2 control-label">Price</label>
                    <div class="col-sm-10">
                        <?php echo $result['Offers']['Offer']['OfferListing']['Price']['CurrencyCode']; ?> <?php echo $result['Offers']['Offer']['OfferListing']['Price']['FormattedPrice']; ?>
                        (-<?php echo $result['Offers']['Offer']['OfferListing']['AmountSaved']['CurrencyCode']; ?> <?php echo $result['Offers']['Offer']['OfferListing']['AmountSaved']['FormattedPrice']; ?>  <?php echo $result['Offers']['Offer']['OfferListing']['PercentageSaved']; ?>%)
                    </div>
                </div>
<?php
                endif;
                if(!isset($result['EditorialReviews']['EditorialReview'][0])){
                    $result['EditorialReviews']['EditorialReview'] = array($result['EditorialReviews']['EditorialReview']);
                }
                if(isset($result['EditorialReviews']['EditorialReview'])):
                    foreach($result['EditorialReviews']['EditorialReview'] as $editorialReview): 
?>
                <div class="form-group">
                    <label for="asin_str" class="col-sm-2 control-label"><?php echo $editorialReview['Source']; ?></label>
                    <div class="col-sm-10">
                        <?php echo $editorialReview['Content']; ?>
                    </div>
                </div>
<?php
                    endforeach;
                endif;
?>

            </form>
        </div>
    </div>
</div>
<?php
        endif;
    }
?>
