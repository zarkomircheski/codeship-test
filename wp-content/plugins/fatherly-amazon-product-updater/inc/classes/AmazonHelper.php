<?php

namespace FatherlyPlugin\APU;

use DateTime;

/**
 * Class AmazonHelper
 * @package FatherlyPlugin\APU
 * This class contains various methods to allow us to easily interact with the Amazon API as well as interact with our
 * products table to perform CRU operations.
 */
class AmazonHelper
{

    /**
     * @var $tableName |string
     */
    public $tableName = 'amazon_products';
    
    /**
     * init
     * Factory method for the AmazonHelper class.
     * @return AmazonHelper
     */
    public static function init()
    {
        return new self;
    }

    /**
     * createNewProduct
     * This is the method called anytime that a product shortcode is inserted with an Amazon link. It will first
     * get us the full product URL if the user has given us the share URL it will then get the ASIN from that URL and
     * perform a check to see if we already have the product in our DB. If the product exists then we fetch that row and
     * return it. If the product doesn't exist then we create it and return the row.
     * @param $url
     * @return array|null|object|void
     */
    public function createNewProduct($url)
    {
        if (strpos($url, 'a.co') !== false) {
            //This means that the product URL is a share URl and we need to get the full URL for the product
            $url = $this->getFullProductURLFromShare($url);
        }
        if ($url) {
            $productASIN = $this->getProductASINByURL($url);
            if (!$product = $this->fetchProductFromDBByASIN($productASIN)) {
                $productData = $this->getProductInformationFromAmazonByASIN($productASIN);
                if (!array_key_exists('errors', $productData)) {
                    return $this->insertAmazonProductIntoDB($productData[0]);
                } else {
                    return $productData;
                }
            } else {
                return $product;
            }
        }
    }

    /**
     * getFullProductURLFromShare
     * Amazon has different URL types for their products one of which is the `a.co` links that are used for sharing.
     * This URL doesn't contain the product's ASIN but it does immediately 301 you to the full product url. This method
     * will take that share url and then extract the location header we get when we follow it which will be the full
     * product URL.
     * @param $shareLink
     * @return bool|string
     */
    public function getFullProductURLFromShare($shareLink)
    {
        $ch = curl_init($shareLink);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_VERBOSE, 1);
        curl_setopt($ch, CURLOPT_HEADER, 1);
        $headers = curl_exec($ch);
        curl_close($ch);

        if (preg_match('/^Location: (.+)$/im', $headers, $matches)) {
            return trim($matches[1]);
        } else {
            return false;
        }
    }

    /**
     * getProductASINByURL
     * This will accept the Amazon url for a product and then get the ASIN for that product from the URL.
     * @param $productFullUrl
     * @return mixed
     */
    public function getProductASINByURL($productFullUrl)
    {
        /*
         * Sometimes an amazon product will have `/product` after the `/gp` in the url and this breaks the regex.
         * That part of the url is not needed since we only care about the ASIN in the url so we strip that out.
         */
        $productFullUrl = str_replace("/product/", '/', $productFullUrl);
        $productASINPattern = '/(?:dp|o|gp|-)\/(B[0-9]{2}[0-9A-Z]{7}|[0-9]{9}(?:X|[0-9]))/';
        preg_match($productASINPattern, $productFullUrl, $matches);
        return $matches[1];
    }

    /**
     * fetchProductFromDBByASIN
     * Fetches an Amazon product from our DB by its ASIN. Also checks to see if that product needs an update while it's
     * at it. If the product needs an update then an event is scheduled to update the product so that we aren't blocking
     * a users request.
     * @param $productASIN
     * @return array|null|object|void
     */
    public function fetchProductFromDBByASIN($productASIN)
    {
        global $wpdb;
        $table = $wpdb->prefix . $this->tableName;
        $query = sprintf("SELECT * FROM %s WHERE product_asin = '%s'", $table, $productASIN);
        $row = $wpdb->get_row($query, ARRAY_A);
        $this->checkIfAmazonProductNeedsUpdate($row);
        return $row;
    }

    /**
     * checkIfAmazonProductNeedsUpdate
     * Checks if the `updated_at` date of a product is equal to or greater than 1 day ago. If it is then this will
     * call a method to check if we can schedule an event to update that product and if we can that method will do it.
     * @param $productData
     */
    public function checkIfAmazonProductNeedsUpdate($productData)
    {
        $currentTime = new DateTime();
        $updatedTime = new DateTime($productData['updated_at']);
        $timeSinceLastUpdate = $currentTime->diff($updatedTime);
        if ($timeSinceLastUpdate->days >= 1) {
            $this->maybeDispatchAmazonProductUpdateEvent($productData);
        }
    }

    /**
     * maybeDispatchAmazonProductUpdateEvent
     * Checks if the product update event can be scheduled. The even can only be scheduled when there are less than 30
     * items already in the WP cron queue and when the option to enable the daily targeted update is enabled.
     * @param $productData
     */
    public function maybeDispatchAmazonProductUpdateEvent($productData)
    {
        /*
         * @var $cronQueueSize
         * This is the total amount of cron jobs scheduled in WP. This includes jobs that aren't currently running. Most of
         * the jobs here are scheduled to run in the future but for queue size control that doesn't matter much. Our current
         * job count is around 11 so if that jumps past 30 then definitely don't want to add another job to the schedule.
         */
        $cronQueueSize = count(get_option('cron'));
        if (function_exists('get_field')) {
            if (get_field('enable_daily_update', 'option') && $cronQueueSize < 30) {
                wp_schedule_single_event(time(), 'update_amazon_product', array($productData));
            }
        } else {
            // This will fire only when ACF is not available. In that case we want to check the queue size only.
            if ($cronQueueSize < 30) {
                wp_schedule_single_event(time(), 'update_amazon_product', array($productData));
            }
        }
    }

    /**
     * getProductInformationFromAmazonByASIN
     * Accepts an Amazon product ASIN and then makes a call to Amazon's associates API to get the information about that
     * product. It then returns an array of data for that product which can be passed into an insert or update method.
     * @param $productASIN
     * @return array
     */
    public function getProductInformationFromAmazonByASIN($productASIN)
    {
        if (is_array($productASIN)) {
            $ids = " \"ItemIds\": [\"".implode("\",\"", $productASIN)."\"],";
        } else {
            $ids = " \"ItemIds\": [\"".$productASIN."\"],";
        }

        $items = [];
        $serviceName="ProductAdvertisingAPI";
        $region="us-east-1";
        $accessKey="AKIAJKJ37XHEX4FDDZBA";
        $secretKey="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";
        $payload="{"
            .$ids
            ." \"Resources\": ["
            ."  \"Images.Primary.Large\","
            ."  \"ItemInfo.Features\","
            ."  \"ItemInfo.Title\","
            ."  \"Offers.Listings.Price\""
            ." ],"
            ." \"PartnerTag\": \"fatherlycom-20\","
            ." \"PartnerType\": \"Associates\","
            ." \"Marketplace\": \"www.amazon.com\""
            ."}";
        $host="webservices.amazon.com";
        $uriPath="/paapi5/getitems";
        $awsv4 = new AwsV4($accessKey, $secretKey);
        $awsv4->setRegionName($region);
        $awsv4->setServiceName($serviceName);
        $awsv4->setPath($uriPath);
        $awsv4->setPayload($payload);
        $awsv4->setRequestMethod("POST");
        $awsv4->addHeader('content-encoding', 'amz-1.0');
        $awsv4->addHeader('content-type', 'application/json; charset=utf-8');
        $awsv4->addHeader('host', $host);
        $awsv4->addHeader('x-amz-target', 'com.amazon.paapi5.v1.ProductAdvertisingAPIv1.GetItems');
        $headers = $awsv4->getHeaders();
        $headerString = "";
        foreach ($headers as $key => $value) {
            $headerString .= $key . ': ' . $value . "\r\n";
        }
        $params = array (
            'http' => array (
                'header' => $headerString,
                'method' => 'POST',
                'content' => $payload
            )
        );

        $stream = stream_context_create($params);
        $fp = @fopen('https://'.$host.$uriPath, 'rb', false, $stream);
        if (! $fp) {
            return $items;
        }
        $response = @stream_get_contents($fp);
        if ($response === false) {
            return $items;
        }

        $data = json_decode($response, true);


        if ($data['ItemsResult']['Items']) {
            for($i = 0; $i < count($data['ItemsResult']['Items']); $i++) {
                $items[] = $this->extractAmazonProductDataFromAPIResponse($data['ItemsResult']['Items'][$i]);
            }
        }
        if ($data['Errors']) {
            for($i = 0; $i < count($data['Errors']); $i++) {
                //This means there's only one product with an error in this request
                $items['errors'][] = $this->parseAmazonErrorData($data['Errors'][$i]);
            }
        }
        return $items;
    }

    protected function parseAmazonErrorData($error)
    {
        if (isset($error['Message'])) {
            $return = array();
            $return['message'] = $error['Message'];
            $productASINPattern = '/(B[0-9]{2}[0-9A-Z]{7}|[0-9]{9}(?:X|[0-9]))/';
            preg_match($productASINPattern, $error['Message'], $matches);
            if ($matches[1]) {
                $return['product'] = $matches[1];
            }
            return $return;
        }
    }

    protected function extractAmazonProductDataFromAPIResponse($itemJSON)
    {
        $productData = array();
        $productData['asin'] = $itemJSON['ASIN'];
        $productData['link'] = $itemJSON['DetailPageURL'];

        if ($itemJSON['ItemInfo']['Title'] && $itemJSON['ItemInfo']['Title']['DisplayValue']) {
            $productData['title'] = $itemJSON['ItemInfo']['Title']['DisplayValue'];
        }
        if(is_array($itemJSON['Offers']['Listings']) && $itemJSON['Offers']['Listings'][0]['Price'] && $itemJSON['Offers']['Listings'][0]['Price']['Amount']) {
            $productData['price'] = $itemJSON['Offers']['Listings'][0]['Price']['Amount'];
        }
        if (is_array($itemJSON['ItemInfo']['Features']['DisplayValues'])) {
            $productData['description'] = "<ul><li>" . implode('</li><li>', $itemJSON['ItemInfo']['Features']['DisplayValues']) . "</li></ul>";
        } else {
            $productData['description'] = $itemJSON['ItemInfo']['Features']['DisplayValues'];
        }
        if ($itemJSON['Images']['Primary'] && $itemJSON['Images']['Primary']['Large']) {
            $productData['image'] = $itemJSON['Images']['Primary']['Large']['URL'];
        }

        if (!$productData['price']) {
            $productData['error_message'] = '';
            if (is_array($itemJSON['Variations']) && is_array($itemJSON['Variations']['Item'])) {
                $productData['error_message'] .= "We were unable to get information on the product you provided.<br> We think that one of the URL's below match this product, please confirm and if it does please retry adding <br> the product using the link we provided otherwise you may add the product manually using the form below.<br>";
                foreach ($itemJSON['Variations']['Item'] as $itemVariant) {
                    $productData['error_message'] .= "<a target='_blank' href='https://www.amazon.com/dp/{$itemVariant['ASIN']}'>https://www.amazon.com/dp/{$itemVariant['ASIN']}</a><br>";
                }
            } elseif (intval($itemJSON['OfferSummary']['TotalNew']) == 0) {
                $productData['error_message'] .= "This product is showing out of stock with the API and will need to be added manually.\n";
            } elseif (is_array($itemJSON['Offers']['Listings']) && $itemJSON['Offers']['Listings'][0]['ViolatesMAP'] === true) {
                $productData['error_message'] = "Price is too low to be available through API";
            } else {
                $productData['error_message'] = "There was a problem getting information for this product from the Amazon API, It will need to be added manually\n";
            }
        }
        return $productData;
    }

    /**
     * insertAmazonProductIntoDB
     * Accepts an array of product data gathered from the Amazon API and then inserts a new row into our DB for that
     * product.
     * @param $productData
     * @return array
     */
    public function insertAmazonProductIntoDB($productData)
    {
        global $wpdb;
        $table = $wpdb->prefix . $this->tableName;
        $rowData = array(
            'product_asin' => $productData['asin'],
            'product_link' => $productData['link'],
            'product_title' => $productData['title'] ?: null,
            'product_image' => $productData['image'] ?: null,
            'product_description' => $productData['description'] ?: null,
            'product_price' => $productData['price'],
            'created_at' => current_time('mysql'),
            'updated_at' => current_time('mysql')
        );
        $wpdb->insert($table, $rowData);
        return $rowData;
    }

    /**
     * deleteAmazonProductFromDB
     * Accepts an Amazon product ASIN as a parameter and then deletes that record from the products table.
     * @param $productASIN
     * @return false|int
     */
    public function deleteAmazonProductFromDB($productASIN)
    {
        global $wpdb;
        $table = $wpdb->prefix . $this->tableName;
        return $wpdb->delete($table, array('product_asin' => $productASIN));
    }

    /**
     * updateAmazonProducts
     * This is the bulk update method called weekly which will fetch all stale products and then trigger an update on
     * each one.
     */
    public function updateAmazonProducts()
    {
        $staleProductsChunked = array_chunk($this->fetchStaleProducts(), 10, true);
        if (count($staleProductsChunked) > 0) {
            foreach ($staleProductsChunked as $staleProducts) {
                $this->updateProductDataFromAmazon($staleProducts, true);
                sleep(2);
            }
        }
    }

    /**
     * fetchStaleProducts
     * Fetches all products in the DB that have an `updated_at` value older than 5 days ago.
     * @return array|null|object
     */
    public function fetchStaleProducts()
    {
        global $wpdb;
        $date = new DateTime("5 days ago");
        $dateFormatted = $date->format('Y-m-d H:i:s');
        $table = $wpdb->prefix . $this->tableName;
        $query = sprintf("SELECT * FROM %s WHERE updated_at < '%s' order by updated_at ASC", $table, $dateFormatted);
        return $wpdb->get_results($query, ARRAY_A);
    }

    /**
     * updateProductDataFromAmazon
     * This accepts product data for a product from the DB and then makes a call to Amazon and checks each field on the
     * product for updates. These updates are then applied to the DB or if no updates are needed we simply update the
     * `updated_at` timestamp to show that checked it.
     * @param $oldData
     */
    public function updateProductDataFromAmazon($oldData, $multiple = false)
    {
        if ($multiple) {
            $productASINS = array();
            foreach ($oldData as $key => $od) {
                $productASINS[] = $od['product_asin'];
                $oldDataFormatted[$od['product_asin']] = $od;
            }
            $updatedProductsInfo = $this->getProductInformationFromAmazonByASIN($productASINS);
            if ($updatedProductsInfo && array_key_exists('errors', $updatedProductsInfo)) {
                $errorProducts = array();
                foreach ($updatedProductsInfo['errors'] as $error) {
                    error_log(sprintf("Amazon Updater Error: %s", $error['message']));
                    if ($error['product']) {
                        $errorProducts[] = $error['product'];
                    }
                }
                if (count($errorProducts > 0)) {
                    $currentErrProducts = get_option('fth_amazon_error_products');
                    if ($currentErrProducts) {
                        update_option('fth_amazon_error_products', array_unique(array_merge($currentErrProducts, $errorProducts)));
                    } else {
                        add_option('fth_amazon_error_products', $errorProducts);
                    }
                }
                unset($updatedProductsInfo['errors']);
            }
            if (count($updatedProductsInfo) > 0) {
                foreach ($updatedProductsInfo as $updatedProductInfo) {
                    $oldProductInfo = $oldDataFormatted[$updatedProductInfo['asin']];
                    $updatedProductInfoFormatted = $this->convertAmazonProductKeysToWP($updatedProductInfo);
                    $updatedFields = array_filter(array_diff($updatedProductInfoFormatted, $oldProductInfo));
                    $updatedFields['updated_at'] = current_time('mysql');
                    $update = $this->updateProductInDB($oldProductInfo['id'], $updatedFields);
                }
            }
        } else {
            $updatedInformation = $this->getProductInformationFromAmazonByASIN($oldData['product_asin']);
            if (array_key_exists('errors', $updatedInformation)) {
                $updateError = $updatedInformation['errors'][0];
                error_log(sprintf("Amazon Updater Error: %s", $updateError['message']));
                if (array_key_exists('product', $updateError)) {
                    $currentErrProducts = get_option('fth_amazon_error_products');
                    if ($currentErrProducts) {
                        array_push($currentErrProducts, $updateError['product']);
                        update_option('fth_amazon_error_products', array_unique($currentErrProducts));
                    } else {
                        add_option('fth_amazon_error_products', array($updateError['product']));
                    }
                }
            } else {
                $updatedInformationFormatted = $this->convertAmazonProductKeysToWP($updatedInformation[0]);
                $errorProducts = get_option('fth_amazon_error_products');
                if (($key = array_search($updatedInformationFormatted['product_asin'], $errorProducts)) !== false) {
                    unset($errorProducts[$key]);
                    update_option('fth_amazon_error_products', $errorProducts);
                }
                $updatedFields = array();
                foreach ($updatedInformationFormatted as $field => $value) {
                    if (!empty($value) && $value !== $oldData[$field]) {
                        $updatedFields[$field] = $value;
                    }
                }
                $updatedFields['updated_at'] = current_time('mysql');
                $update = $this->updateProductInDB($oldData['id'], $updatedFields);
            }

        }
    }

    /**
     * convertAmazonProductKeysToWP
     * Essentially this just prepends `product_` to all the array keys in the product data so we can match the DB table.
     * @param $productData
     * @return array
     */
    public function convertAmazonProductKeysToWP(
        $productData
    ) {
        return array(
            'product_asin' => $productData['asin'],
            'product_link' => $productData['link'],
            'product_title' => $productData['title'],
            'product_image' => $productData['image'],
            'product_description' => $productData['description'],
            'product_price' => $productData['price'],
        );
    }

    /**
     * updateProductInDB
     * Takes the row id and an array of fields that need to be updated for a product and then updates that product
     * in the DB.
     * @param $id
     * @param $updateData
     * @return false|int
     */
    public function updateProductInDB(
        $id,
        $updateData
    ) {
        global $wpdb;
        $table = $wpdb->prefix . $this->tableName;
        return $wpdb->update($table, $updateData, array('id' => $id));
    }

    /**
     * getAdminPageData
     * Fetches Amazon products from our DB for display on the admin page. This method will accepts an array containing
     * params for ordering the returned data and also for searching based on user input.
     * @return array|null|object
     */
    public function getAdminPageData($order = null)
    {
        global $wpdb;
        $table = $wpdb->prefix . $this->tableName;
        if (is_array($order)) {
            $query = "SELECT * FROM {$table}";
            // The `s` key being present means that there's a search being performed
            if (array_key_exists('s', $order)) {
                /*
                 * If someone is performing a search we want to ensure that if it's an ASIN we return the product
                 * matching that ASIN.
                 */
                $query .= sprintf(" WHERE product_asin = '%s' OR", $order['s']);
                /*
                 * Here we split multiple words in a search so we can query them individually like keywords. If there's
                 * only one item in the exploded array then they either searched by an ASIN or only had one word in
                 * their search query.
                 */
                $keywords = explode(" ", $order['s']);
                if (count($keywords) > 1) {
                    $keyCount = 0;
                    $searchSQL = "";
                    foreach ($keywords as $key) {
                        if ($keyCount > 0) {
                            $searchSQL .= " AND";
                        }
                        $searchSQL .= sprintf(" product_title LIKE '%%%s%%'", $key);
                        $keyCount++;
                    }
                    /*
                     * Here we surround our keywords part of the search query with parenthesis so that they are
                     * contained unto themselves and can be tacked on to the ASIN query with an `OR`.
                     */
                    $query .= " ({$searchSQL})";
                } else {
                    /*
                     * If there isn't multiple keywords then we just pass the `s` param into our query directly.
                     */
                    $query .= sprintf(" product_title LIKE '%%%s%%'", $order['s'], $order['s']);
                }
            }
            /*
             * This will check to see if there's an `orderby` param passed to this method.
             * If so then we honor that here. If an `orderby` is provided then the `order` param will always be present
             * since it defaults to `ASC`.
             */
            if (array_key_exists('orderby', $order)) {
                $query .= sprintf(" ORDER BY %s %s", $order['orderby'], $order['order']);
            } else {
                /*
                 * If no ordering params were defined then we sort by `created_at` as a default.
                 */
                $query .= " ORDER BY created_at DESC";
            }
            return $wpdb->get_results($query, 'ARRAY_A');
        } else {
            return $wpdb->get_results("SELECT * FROM {$table} ORDER BY created_at DESC", 'ARRAY_A');
        }
    }


    /**
     * getProductErrorsAdminPageData
     * Loads all of the invalid products for the product errors page so that we can fix issues with those products or
     * delete them.
     * @return array | null
     */
    public function getProductErrorsAdminPageData()
    {
        global $wpdb;
        $table = $wpdb->prefix . 'posts';
        $errorProductASINs = get_option('fth_amazon_error_products');
        if ($errorProductASINs) {
            foreach ($errorProductASINs as $errorProductASIN) {
                $pageData[$errorProductASIN]['product_data'] = $this->fetchProductFromDBByASIN($errorProductASIN);
            }
            return $pageData;
        } else {
            return null;
        }
    }
}


// Code provided from form Amazon used to structure the post call made to their API
class AwsV4
{

    private $accessKey = null;
    private $secretKey = null;
    private $path = null;
    private $regionName = null;
    private $serviceName = null;
    private $httpMethodName = null;
    private $queryParametes = array ();
    private $awsHeaders = array ();
    private $payload = "";

    private $HMACAlgorithm = "AWS4-HMAC-SHA256";
    private $aws4Request = "aws4_request";
    private $strSignedHeader = null;
    private $xAmzDate = null;
    private $currentDate = null;

    public function __construct($accessKey, $secretKey)
    {
        $this->accessKey = $accessKey;
        $this->secretKey = $secretKey;
        $this->xAmzDate = $this->getTimeStamp();
        $this->currentDate = $this->getDate();
    }

    function setPath($path)
    {
        $this->path = $path;
    }

    function setServiceName($serviceName)
    {
        $this->serviceName = $serviceName;
    }

    function setRegionName($regionName)
    {
        $this->regionName = $regionName;
    }

    function setPayload($payload)
    {
        $this->payload = $payload;
    }

    function setRequestMethod($method)
    {
        $this->httpMethodName = $method;
    }

    function addHeader($headerName, $headerValue)
    {
        $this->awsHeaders [$headerName] = $headerValue;
    }

    private function prepareCanonicalRequest()
    {
        $canonicalURL = "";
        $canonicalURL .= $this->httpMethodName . "\n";
        $canonicalURL .= $this->path . "\n" . "\n";
        $signedHeaders = '';
        foreach ($this->awsHeaders as $key => $value) {
            $signedHeaders .= $key . ";";
            $canonicalURL .= $key . ":" . $value . "\n";
        }
        $canonicalURL .= "\n";
        $this->strSignedHeader = substr($signedHeaders, 0, - 1);
        $canonicalURL .= $this->strSignedHeader . "\n";
        $canonicalURL .= $this->generateHex($this->payload);
        return $canonicalURL;
    }

    private function prepareStringToSign($canonicalURL)
    {
        $stringToSign = '';
        $stringToSign .= $this->HMACAlgorithm . "\n";
        $stringToSign .= $this->xAmzDate . "\n";
        $stringToSign .= $this->currentDate . "/" . $this->regionName . "/" . $this->serviceName . "/" . $this->aws4Request . "\n";
        $stringToSign .= $this->generateHex($canonicalURL);
        return $stringToSign;
    }

    private function calculateSignature($stringToSign)
    {
        $signatureKey = $this->getSignatureKey($this->secretKey, $this->currentDate, $this->regionName, $this->serviceName);
        $signature = hash_hmac("sha256", $stringToSign, $signatureKey, true);
        $strHexSignature = strtolower(bin2hex($signature));
        return $strHexSignature;
    }

    public function getHeaders()
    {
        $this->awsHeaders ['x-amz-date'] = $this->xAmzDate;
        ksort($this->awsHeaders);

        // Step 1: CREATE A CANONICAL REQUEST
        $canonicalURL = $this->prepareCanonicalRequest();

        // Step 2: CREATE THE STRING TO SIGN
        $stringToSign = $this->prepareStringToSign($canonicalURL);

        // Step 3: CALCULATE THE SIGNATURE
        $signature = $this->calculateSignature($stringToSign);

        // Step 4: CALCULATE AUTHORIZATION HEADER
        if ($signature) {
            $this->awsHeaders ['Authorization'] = $this->buildAuthorizationString($signature);
            return $this->awsHeaders;
        }
    }

    private function buildAuthorizationString($strSignature)
    {
        return $this->HMACAlgorithm . " " . "Credential=" . $this->accessKey . "/" . $this->getDate() . "/" . $this->regionName . "/" . $this->serviceName . "/" . $this->aws4Request . "," . "SignedHeaders=" . $this->strSignedHeader . "," . "Signature=" . $strSignature;
    }

    private function generateHex($data)
    {
        return strtolower(bin2hex(hash("sha256", $data, true)));
    }

    private function getSignatureKey($key, $date, $regionName, $serviceName)
    {
        $kSecret = "AWS4" . $key;
        $kDate = hash_hmac("sha256", $date, $kSecret, true);
        $kRegion = hash_hmac("sha256", $regionName, $kDate, true);
        $kService = hash_hmac("sha256", $serviceName, $kRegion, true);
        $kSigning = hash_hmac("sha256", $this->aws4Request, $kService, true);

        return $kSigning;
    }

    private function getTimeStamp()
    {
        return gmdate("Ymd\THis\Z");
    }

    private function getDate()
    {
        return gmdate("Ymd");
    }
}
