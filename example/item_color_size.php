<form class="form-horizontal" method="post" action="?example=item_color_size">
    <div class="form-group">
        <label for="pasin_str" class="col-sm-2 control-label">PARENT ASIN</label>
        <div class="col-sm-10">
            <input type="text" name="pasin_str" class="form-control" id="pasin_str" placeholder="ex : B001CW0A5K" value="<?php echo (isset($_POST['pasin_str']))?$_POST['pasin_str']:''; ?>">
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
    if(isset($_POST['pasin_str'])){
        require("amazon_api_class.php");

        $obj = new AmazonProductAPI();

        try
        {
            $result = $obj->getItemColorSize($_POST['pasin_str']);
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
          <h3 class="panel-title"><?php echo $result['ASIN']; ?> / <?php echo $result['ParentASIN']; ?></h3>
        </div>
        <div class="panel-body">
            <form class="form-horizontal" method="post" action="?example=item_by_asin">
                <div class="form-group">
                    <label for="asin_str" class="col-sm-2 control-label">ASIN</label>
                    <div class="col-sm-10">
<?php
                    if($result['ParentASIN'] != $_POST['pasin_str']):
?>
                        <span class="label label-danger"><?php echo $result['ASIN']; ?></span>
<?php
                    else:
?>
                        <?php echo $result['ASIN']; ?>
<?php
                    endif;
?>
                    </div>
                </div>
                <div class="form-group">
                    <label for="asin_str" class="col-sm-2 control-label">ParentASIN</label>
                    <div class="col-sm-10">
<?php
                    if($result['ParentASIN'] == $_POST['pasin_str']):
?>
                        <span class="label label-success"><?php echo $result['ParentASIN']; ?></span>
<?php
                    else:
?>
                        <?php echo $result['ParentASIN']; ?>
<?php
                    endif;
?>
                    </div>
                </div>
<?php
                    if(isset($result['Variations']['TotalVariations'])):
?>                
                <div class="form-group">
                    <label for="asin_str" class="col-sm-2 control-label">count</label>
                    <div class="col-sm-10">
                        <?php echo $result['Variations']['TotalVariations']; ?>
                    </div>
                </div>
                <div class="form-group">
                    <label for="asin_str" class="col-sm-2 control-label">規格品種類</label>
                    <div class="col-sm-10">
                        <div class="btn-group" role="group" aria-label="">
<?php
                    if(!is_array($result['Variations']['VariationDimensions']['VariationDimension'])){
                        $result['Variations']['VariationDimensions']['VariationDimension'] = array($result['Variations']['VariationDimensions']['VariationDimension']);
                    }
                    foreach($result['Variations']['VariationDimensions']['VariationDimension'] as $item):
?>                        
                        <button type="button" class="btn btn-default"><?php echo $item; ?></button>
<?php
                    endforeach;
?>                        
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="asin_str" class="col-sm-2 control-label">Detail List</label>
                    <div class="col-sm-10">
                        <table class="table table-hover">
                            <tr>
                                <th>ASIN</th><th>規格</th><th>debug</th>
                            </tr>
<?php
                    foreach($result['Variations']['Item'] as $item):
?>
                            <tr>
                                <td><?php echo $item['ASIN']; ?></td>
                                <td>
                                    <div class="btn-group" role="group" aria-label="">
<?php
                        foreach($result['Variations']['VariationDimensions']['VariationDimension'] as $type):
?>                        
                        
                                        <button type="button" class="btn btn-default"><?php echo $item['ItemAttributes'][$type]; ?></button>
                      
<?php
                        endforeach;
?>                        
                                    </div>
                                </td>
                                <td><?php (isset($_POST['debug']) && $_POST['debug'] === '1')?var_dump($item):''; ?></td>
                            </tr>
<?php
                    endforeach;
?>
                        </table>
                    </div>
                </div>
<?php
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
