<?php

    /**
     * Class to access Amazons Product Advertising API
     * @author Sameer Borate
     * @link http://www.codediesel.com
     * @version 1.0
     * All requests are not implemented here. You can easily
     * implement the others from the ones given below.
     */


    /*
    Permission is hereby granted, free of charge, to any person obtaining a
    copy of this software and associated documentation files (the "Software"),
    to deal in the Software without restriction, including without limitation
    the rights to use, copy, modify, merge, publish, distribute, sublicense,
    and/or sell copies of the Software, and to permit persons to whom the
    Software is furnished to do so, subject to the following conditions:

    The above copyright notice and this permission notice shall be included in
    all copies or substantial portions of the Software.

    THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
    IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
    FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL
    THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
    LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING
    FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER
    DEALINGS IN THE SOFTWARE.
    */
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    require_once 'aws_signed_request.php';
    require_once 'key.php';

    class AmazonProductAPI
    {
        /**
         * Your Amazon Access Key Id
         * @access private
         * @var string
         */
        private $public_key     = PUBLIC_KEY;

        /**
         * Your Amazon Secret Access Key
         * @access private
         * @var string
         */
        private $private_key    = PRIVATE_KEY;

        /**
         * Your Amazon Associate Tag
         * Now required, effective from 25th Oct. 2011
         * @access private
         * @var string
         */
        private $associate_tag  = ASSOCIATE_TAG;

        /**
         * Constants for product types
         * @access public
         * @var string
         */

        /*
            Only three categories are listed here.
            More categories can be found here:
            http://docs.amazonwebservices.com/AWSECommerceService/latest/DG/APPNDX_SearchIndexValues.html
        */
        const MUSIC = "Music";
        const DVD   = "DVD";
        const GAMES = "VideoGames";


        /**
         * Check if the xml received from Amazon is valid
         *
         * @param mixed $response xml response to check
         * @return bool false if the xml is invalid
         * @return mixed the xml response if it is valid
         * @return exception if we could not connect to Amazon
         */
        private function verifyXmlResponse($response)
        {
            if ($response === False)
            {
                throw new Exception("Could not connect to Amazon");
            }
            else
            {

                    return $this->objectToArray($response);

            }
        }


        /**
         * Query Amazon with the issued parameters
         *
         * @param array $parameters parameters to query around
         * @return simpleXmlObject xml query response
         */
        private function queryAmazon($parameters)
        {
            return aws_signed_request("com", $parameters, $this->public_key, $this->private_key, $this->associate_tag);
        }


        /**
         * Return details of products searched by various types
         *
         * @param string $search search term
         * @param string $category search category
         * @param string $searchType type of search
         * @return mixed simpleXML object
         */
        public function searchProducts($search, $category, $searchType = "UPC")
        {
            $allowedTypes = array("UPC", "TITLE", "ARTIST", "KEYWORD");
            $allowedCategories = array("Music", "DVD", "VideoGames");

            switch($searchType)
            {
                case "UPC" :    $parameters = array("Operation"     => "ItemLookup",
                                                    "ItemId"        => $search,
                                                    "SearchIndex"   => $category,
                                                    "IdType"        => "UPC",
                                                    "ResponseGroup" => "Medium");
                                break;

                case "TITLE" :  $parameters = array("Operation"     => "ItemSearch",
                                                    "Title"         => $search,
                                                    "SearchIndex"   => $category,
                                                    "ResponseGroup" => "Medium");
                                break;

            }

            $xml_response = $this->queryAmazon($parameters);

            return $this->verifyXmlResponse($xml_response);

        }


        /**
         * Return details of a product searched by UPC
         *
         * @param int $upc_code UPC code of the product to search
         * @param string $product_type type of the product
         * @return mixed simpleXML object
         */
        public function getItemByUpc($upc_code, $product_type)
        {
            $parameters = array("Operation"     => "ItemLookup",
                                "ItemId"        => $upc_code,
                                "SearchIndex"   => $product_type,
                                "IdType"        => "UPC",
                                "ResponseGroup" => "Medium");

            $xml_response = $this->queryAmazon($parameters);

            return $this->verifyXmlResponse($xml_response);

        }
        /**
         * 要取得其他顏色與尺寸 ItemId是getItemByAsin XML資訊裡的parent ASIN TAG
         * ResponseGroup用VariationMatrix就可以了
         *
         * @param int $upc_code UPC code of the product to search
         * @param string $product_type type of the product
         * @return mixed simpleXML object
         */
        public function getItemColorSize($asin_code)
        {
          $parameters = array("Operation"     => "ItemLookup",
                              "ItemId"        => $asin_code,
                              "Condition"     => "All",

                              "ResponseGroup" => "VariationMatrix");

          $xml_response = $this->queryAmazon($parameters);

          return $this->verifyXmlResponse($xml_response);
        }


        /**
         * Return details of a product searched by ASIN
         *
         * @param int $asin_code ASIN code of the product to search
         * ResponseGroup 回傳資訊
         * http://docs.aws.amazon.com/AWSECommerceService/latest/DG/CHAP_ResponseGroupsList.html
         * @return mixed simpleXML object
         */
        public function getItemByAsin($asin_code)
        {
            $parameters = array("Operation"     => "ItemLookup",
                                "ItemId"        => $asin_code,
                                "Condition"     => "All",
                                "ResponseGroup" => "ItemAttributes,Images");


            $xml_response = $this->queryAmazon($parameters);

            return $this->verifyXmlResponse($xml_response);
        }


        /**
         * Return details of a product searched by keyword
         *
         * @param string $keyword keyword to search
         * @param string $product_type type of the product
         * @return mixed simpleXML object
         */
        public function getItemByKeyword($keyword, $product_type)
        {
            $parameters = array("Operation"   => "ItemSearch",
                                "Keywords"    => $keyword,
                                "SearchIndex" => $product_type);

            $xml_response = $this->queryAmazon($parameters);

            return $this->verifyXmlResponse($xml_response);
        }

        /**
         * 取的分類階層資訊
         * http://docs.aws.amazon.com/AWSECommerceService/latest/DG/BrowseNodeLookup.html
         *
         * @param string $browse_node_id 節點ID 請參考網址http://docs.aws.amazon.com/AWSECommerceService/latest/DG/localevalues.html
         * @param string $response_group 暫時不用 回傳資訊 Valid Values: MostGifted | NewReleases | MostWishedFor | TopSellers
         * @return mixed simpleXML object
         */
        public function getBrowseNodeInfo($browse_node_id,$response_group)
        {
          $parameters = array("Operation"   => "BrowseNodeLookup",
                              "BrowseNodeId"    =>$browse_node_id,
                              "ResponseGroup" => $response_group);

          $xml_response = $this->queryAmazon($parameters);

          return $this->verifyXmlResponse($xml_response);
        }

        /**
        * 整合之前的查詢單品SIZE與圖片 產生一個比較完整的可以下單的單品頁
        *
        *
        **/
        public function getCartPage($asin_code)
        {

            $item_xml = $this->getItemByAsin($asin_code);

            //$this->_pre($item_xml);

          
            if(isset( $item_xml['Items']['Item']['ParentASIN']))
            {

               $parent_xml = $this->getItemColorSize($item_xml['Items']['Item']['ParentASIN']);
               //$this->_pre($parent_xml);
            }

            return ['item_xml' => $item_xml,'p_item_xml'=>$parent_xml];
        }

        private function _pre($code)
        {
          echo '<PRE>';
          if(is_array($code) || is_object($code))
          {

            print_r($code);
          }
          else {
            echo $code;
            echo '==';
          }

          echo '</PRE>';
        }


        /*
        *
        * Convert an object to an array
        *
        * @param    object  $object The object to convert
        * @reeturn      array
        *
        */
        public function objectToArray( $object )
        {
            if( !is_object( $object ) && !is_array( $object ) )
            {
                return $object;
            }
            if( is_object( $object ) )
            {
                $object = get_object_vars( $object );
            }
            return array_map( array('AmazonProductAPI','objectToArray'), $object );

        }

    }

?>
