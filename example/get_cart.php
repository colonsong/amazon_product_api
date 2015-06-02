<?php
require("amazon_api_class.php");

$obj = new AmazonProductAPI();

try
{
    $result = $obj->getCart();
}
catch(Exception $e)
{
    echo $e->getMessage();
}
