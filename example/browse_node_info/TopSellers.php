<div class="col-sm-12">
    <div class="panel panel-default">
        <div class="panel-heading">
          <h3 class="panel-title"><?php echo $result['BrowseNodeId']; ?> / <?php echo $result['Name']; ?></h3>
        </div>
        <div class="panel-body">
            
            <table class="table table-hover">
                <tr>
                    <th>ASIN</th><th>title</th><th><span class="label label-info">ProductGroup</span></th><th>debug</th>
                </tr>
<?php
        if(isset($result['TopSellers'])):
        foreach($result['TopSellers']['TopSeller'] as $key=>$item):
            $detail = $result['TopItemSet']['TopItem'][$key];
?>
                <tr>
                    <td><?php echo $item['ASIN']; ?></td>
                    <td><a href="<?php echo $detail['DetailPageURL']; ?>" target="_blank"><?php echo $item['Title']; ?></a></td>
                    <td>
                        <span class="label label-info"><?php echo $detail['ProductGroup']; ?></span>
                    </td>
                    <td>
                        <?php (isset($_POST['debug']) && $_POST['debug'] === '1')?var_dump($item):''; ?>
                        <?php (isset($_POST['debug']) && $_POST['debug'] === '1')?var_dump($detail):''; ?>
                    </td>
                </tr>
<?php
        endforeach;
        endif;
?>
            </table>
        </div>
    </div>
</div>
  