<?php

    /* Example usage of the Amazon Product Advertising API */
    require("amazon_api_class.php");

    $obj = new AmazonProductAPI();

    try
    {
        /**
        http://www.amazon.com/Vans-AUTHENTIC-SKATE-SHOES-WHITE/dp/B000UYJBOW/ref=sr_1_6?s=sporting-goods&ie=UTF8&qid=1432261211&sr=1-6&keywords=shoes
        */
        $result = $obj->getItemColorSize("B001CW0A5K");
    }
    catch(Exception $e)
    {
        echo $e->getMessage();
    }
    echo '<PRE>';
    print_r($result);
    echo '</PRE>';
?>
