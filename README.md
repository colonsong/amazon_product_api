# amazon_product_api
amazon_ad_product_api


在該國國家註冊
http://docs.aws.amazon.com/AWSECommerceService/latest/DG/becomingDev.html

名詞解釋
==============
ASIN(Amazon Standard Item Number)
ASIN
Amazon Standard Identification Number, which is an alphanumeric token assigned by Amazon to an item that uniquely identifies it.

==============
IdType

Type of item identifier used to look up an item. All IdTypes except ASINx require a SearchIndex to be specified.

Type: String

Default: ASIN

==============
ItemId

One or more (up to ten) positive integers that uniquely identify an item. The meaning of the number is specified by IdType. That is, if IdType is ASIN, the ItemId value is an ASIN. If ItemIdis an ASIN, a search index cannot be specified in the request.

Type: String

Default: None

Constraints: Must be a valid item ID. For more than one ID, use a comma-separated list of up to ten IDs.


Valid Values: SKU | UPC | EAN | ISBN (US only, when search index is Books). UPC is not valid in the CA locale.

==========
Amazon的产品的页面，比如随便找一个：

http://www.amazon.com/Nokia-Lumia-928-Verizon-Wireless/dp/B00CQAODG4/ref=sr_1_7?s=wireless&ie=UTF8&qid=1371965011&sr=1-7

其中的：B00CQAODG4

就是此产品的ASIN。

而在已知一个ASIN，则可以通过：

http://www.amazon.com/gp/product/ASIN的方式去访问到产品的页面的信息


============
关于IdType和ItemId的关系，后来才搞懂：

AWS中有很多id，ASIN是其中的一种。其他的还有UPC，EAN，ISBN等等。

所以，你发送给amazon服务器一个id的时候，amazon服务器不知道是啥类型的。

所以需要一个参数，用于说明此id是什么类型的，

所以在代码中，会有个参数，叫做IdType。

用的最多的，就属ASIN了。

所以一般会见到：

IdType=ASIN，

然后ItemId=某个产品的ASIN的值，比如：

ItemId=B00CQAODG4

在代码里面，就体现为：

reqDict["IdType"] = "ASIN";
reqDict["ItemId"] = "B00CQAODG4";

====================

不同地區所對照不同的itemID
http://docs.aws.amazon.com/AWSECommerceService/latest/DG/ItemsforSale.html
============

使用ａｐｉ之前 申請ｋｅｙ
取得兩個Access Key ID ＆Associates ID & secret ID

access key分兩種
AWS security credentials (access key ID and secret access key)
X.509 certificates
從這裡申請
account manager
https://affiliate-program.amazon.com/gp/advertising/api/detail/your-account.html?ie=UTF8&pf_rd_i=assoc-api-thank-you-intro&pf_rd_m=ATVPDKIKX0DER&pf_rd_p=&pf_rd_r=&pf_rd_s=assoc-center-1&pf_rd_t=501&ref_=amb_link_83986951_1

Your Security Credentials
https://console.aws.amazon.com/iam/home?#security_credential
得到Access Key ID

=========
WSDL
http://webservices.amazon.com/AWSECommerceService/AWSECommerceService.wsdl
=========
2011年11月后，好像对于所有的request，都要加上一个Associate Tag的，否则貌似返回都是null和错误。
其实很简单，就是去：

http://docs.aws.amazon.com/AWSECommerceService/latest/DG/becomingAssociate.html
=========
request

有了API KEY 與Associates ID 後 就可以開始request
REST請求
http://docs.aws.amazon.com/AWSECommerceService/latest/DG/CHAP_MakingRequestsandUnderstandingResponses.html
＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝
以ｕｓ地區為例 request 網址為
US
http://webservices.amazon.com/onca/xml

https://webservices.amazon.com/onca/xml

http://docs.aws.amazon.com/AWSECommerceService/latest/DG/SummaryofA2SOperations.html


最最常用的就兩類：

ItemSearch：查找產品等信息

http://docs.aws.amazon.com/AWSECommerceService/latest/DG/ItemSearch.html


比如，給定某個關鍵字keyword，查找出一堆相匹配的產品

ItemLookup：已經某特定的產品，想要查詢該產品的更多相關的信息

http://docs.aws.amazon.com/AWSECommerceService/latest/DG/ItemLookup.html

比如已經該產品的ASIN，想要知道該產品的賣家的信息，等等。


相對不那麼常用的有：

BrowseNodeLookup：用於實現實現產品分類的瀏覽和查詢

http://docs.aws.amazon.com/AWSECommerceService/latest/DG/BrowseNodeLookup.html

類似於模擬如下動作：你用瀏覽器打開Amazon主頁，然後針對那麼多的產品分類，一級一級的，去瀏覽不同分類下面的產品


SimilarityLookup：給定某個產品，去查詢與其相似的產品
http://docs.aws.amazon.com/AWSECommerceService/latest/DG/SimilarityLookup.html


和購物車相關的API：用於模擬用戶購買產品，加入購物車，支付等等動作
CartCreate
CartAdd
CartModify
CartClear
CartGet

＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝
論壇
https://forums.aws.amazon.com/forum.jspa?forumID=9


