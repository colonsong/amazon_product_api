<?php
        if(isset($result['Ancestors'])):
            $array_node = getNode($result);
            krsort($array_node);
            $array_node = array_values($array_node);
            array_pop($array_node);
            //var_dump($array_node);
?>
<div class="col-sm-12">
<ol class="breadcrumb">
<?php
                foreach($array_node as $item):
?>
  <li><span class="badge"><?php echo $item['BrowseNodeId']; ?></span> <a href="#"><?php echo $item['Name']; ?></a></li>
<?php
                endforeach;
?>
  <li class="active"><span class="badge"><?php echo $result['BrowseNodeId']; ?></span> <?php echo $result['Name']; ?></li>
</ol>
</div>
<?php
        endif;
?>
<div class="col-sm-5">
    <div class="panel panel-default">
        <div class="panel-heading">
          <h3 class="panel-title"><?php echo $result['BrowseNodeId']; ?> / <?php echo $result['Name']; ?></h3>
        </div>
        <div class="panel-body">
            
            <ul class="list-group">
<?php
        if(isset($result['Children'])):
        foreach($result['Children']['BrowseNode'] as $item):
?>
                <li class="list-group-item">
                    <span class="badge"><?php echo $item['BrowseNodeId']; ?></span>
                    <?php echo $item['Name']; ?>
                </li>
<?php
        endforeach;
        endif;
?>
            </ul>
        </div>
    </div>
</div>
  