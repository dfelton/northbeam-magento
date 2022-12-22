<?php


//≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡
//Generates json for server-side api and javascript firePurchaseEvent
//Returns object containing both json strings
//≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡
function setup_northbeam_objects($order)
{
    //generate all info

    //get order data
    $order_id = (string) $order->getId();
    $purchase_total = (float) $order->getGrandTotal();
    $shipping_total = (float) $order->getShippingAmount();
    $tax = (float) $order->getTaxAmount();
    $currency = $order->getOrderCurrencyCode();
    $time_of_purchase = date('c', time());

    //get customer data
    $customer_name = $order-> getCustomerName();
    $customer_email = $order->getCustomerEmail();

    if($order->getCustomerIsGuest()){
        $customer_id = 'synthetic_' . $order->getCustomerEmail();
    } else {
        $customer_id = (string) $order->getCustomerId();
    }

    $customer_phone_number = $order->getBillingAddress()->getTelephone();
    $customer_ip_address = $order->getRemoteIp();
    $coupon_code = $order->getCouponCode();

    //get shipping data
    $address1 = $order->getShippingAddress()->getStreet()[0];
    $address2 = $order->getShippingAddress()->getStreet()[1] ?? "";
    $city = $order->getShippingAddress()->getCity();
    $state = $order->getShippingAddress()->getRegionCode();
    $zip = $order->getShippingAddress()->getPostcode();
    $country_code_mapping = ["AF" => "AFG", "AX" => "ALA", "AL" => "ALB", "DZ" => "DZA", "AS" => "ASM", "AD" => "AND", "AO" => "AGO", "AI" => "AIA", "AQ" => "ATA", "AG" => "ATG", "AR" => "ARG", "AM" => "ARM", "AW" => "ABW", "AU" => "AUS", "AT" => "AUT", "AZ" => "AZE", "BS" => "BHS", "BH" => "BHR", "BD" => "BGD", "BB" => "BRB", "BY" => "BLR", "BE" => "BEL", "BZ" => "BLZ", "BJ" => "BEN", "BM" => "BMU", "BT" => "BTN", "BO" => "BOL", "BQ" => "BES", "BA" => "BIH", "BW" => "BWA", "BV" => "BVT", "BR" => "BRA", "IO" => "IOT", "BN" => "BRN", "BG" => "BGR", "BF" => "BFA", "BI" => "BDI", "CV" => "CPV", "KH" => "KHM", "CM" => "CMR", "CA" => "CAN", "KY" => "CYM", "CF" => "CAF", "TD" => "TCD", "CL" => "CHL", "CN" => "CHN", "CX" => "CXR", "CC" => "CCK", "CO" => "COL", "KM" => "COM", "CG" => "COG", "CD" => "COD", "CK" => "COK", "CR" => "CRI", "CI" => "CIV", "HR" => "HRV", "CU" => "CUB", "CW" => "CUW", "CY" => "CYP", "CZ" => "CZE", "DK" => "DNK", "DJ" => "DJI", "DM" => "DMA", "DO" => "DOM", "EC" => "ECU", "EG" => "EGY", "SV" => "SLV", "GQ" => "GNQ", "ER" => "ERI", "EE" => "EST", "SZ" => "SWZ", "ET" => "ETH", "FK" => "FLK", "FO" => "FRO", "FJ" => "FJI", "FI" => "FIN", "FR" => "FRA", "GF" => "GUF", "PF" => "PYF", "TF" => "ATF", "GA" => "GAB", "GM" => "GMB", "GE" => "GEO", "DE" => "DEU", "GH" => "GHA", "GI" => "GIB", "GR" => "GRC", "GL" => "GRL", "GD" => "GRD", "GP" => "GLP", "GU" => "GUM", "GT" => "GTM", "GG" => "GGY", "GN" => "GIN", "GW" => "GNB", "GY" => "GUY", "HT" => "HTI", "HM" => "HMD", "VA" => "VAT", "HN" => "HND", "HK" => "HKG", "HU" => "HUN", "IS" => "ISL", "IN" => "IND", "ID" => "IDN", "IR" => "IRN", "IQ" => "IRQ", "IE" => "IRL", "IM" => "IMN", "IL" => "ISR", "IT" => "ITA", "JM" => "JAM", "JP" => "JPN", "JE" => "JEY", "JO" => "JOR", "KZ" => "KAZ", "KE" => "KEN", "KI" => "KIR", "KP" => "PRK", "KR" => "KOR", "KW" => "KWT", "KG" => "KGZ", "LA" => "LAO", "LV" => "LVA", "LB" => "LBN", "LS" => "LSO", "LR" => "LBR", "LY" => "LBY", "LI" => "LIE", "LT" => "LTU", "LU" => "LUX", "MO" => "MAC", "MG" => "MDG", "MW" => "MWI", "MY" => "MYS", "MV" => "MDV", "ML" => "MLI", "MT" => "MLT", "MH" => "MHL", "MQ" => "MTQ", "MR" => "MRT", "MU" => "MUS", "YT" => "MYT", "MX" => "MEX", "FM" => "FSM", "MD" => "MDA", "MC" => "MCO", "MN" => "MNG", "ME" => "MNE", "MS" => "MSR", "MA" => "MAR", "MZ" => "MOZ", "MM" => "MMR", "NA" => "NAM", "NR" => "NRU", "NP" => "NPL", "NL" => "NLD", "NC" => "NCL", "NZ" => "NZL", "NI" => "NIC", "NE" => "NER", "NG" => "NGA", "NU" => "NIU", "NF" => "NFK", "MK" => "MKD", "MP" => "MNP", "NO" => "NOR", "OM" => "OMN", "PK" => "PAK", "PW" => "PLW", "PS" => "PSE", "PA" => "PAN", "PG" => "PNG", "PY" => "PRY", "PE" => "PER", "PH" => "PHL", "PN" => "PCN", "PL" => "POL", "PT" => "PRT", "PR" => "PRI", "QA" => "QAT", "RE" => "REU", "RO" => "ROU", "RU" => "RUS", "RW" => "RWA", "BL" => "BLM", "SH" => "SHN", "KN" => "KNA", "LC" => "LCA", "MF" => "MAF", "PM" => "SPM", "VC" => "VCT", "WS" => "WSM", "SM" => "SMR", "ST" => "STP", "SA" => "SAU", "SN" => "SEN", "RS" => "SRB", "SC" => "SYC", "SL" => "SLE", "SG" => "SGP", "SX" => "SXM", "SK" => "SVK", "SI" => "SVN", "SB" => "SLB", "SO" => "SOM", "ZA" => "ZAF", "GS" => "SGS", "SS" => "SSD", "ES" => "ESP", "LK" => "LKA", "SD" => "SDN", "SR" => "SUR", "SJ" => "SJM", "SE" => "SWE", "CH" => "CHE", "SY" => "SYR", "TW" => "TWN", "TJ" => "TJK", "TZ" => "TZA", "TH" => "THA", "TL" => "TLS", "TG" => "TGO", "TK" => "TKL", "TO" => "TON", "TT" => "TTO", "TN" => "TUN", "TR" => "TUR", "TM" => "TKM", "TC" => "TCA", "TV" => "TUV", "UG" => "UGA", "UA" => "UKR", "AE" => "ARE", "GB" => "GBR", "US" => "USA", "UM" => "UMI", "UY" => "URY", "UZ" => "UZB", "VU" => "VUT", "VE" => "VEN", "VN" => "VNM", "VG" => "VGB", "VI" => "VIR", "WF" => "WLF", "EH" => "ESH", "YE" => "YEM", "ZM" => "ZMB", "ZW" => "ZWE"];
    $country_code = $country_code_mapping[$order->getShippingAddress()->getCountryId()];

    // Loop Over Order Items and add to products array
    $ss_products_array = array();
    $js_products_array = array();
    foreach ($order->getAllVisibleItems() as $item) {
        $ss_prod_item = new stdClass();
        $js_prod_item = new stdClass();

        //Use Variation ID if available
        $product_id = (string) $item->getProductId();
        $ss_prod_item->id = $product_id;
        $js_prod_item->productId = $product_id;

        $variation_id = (string) $item->getSku();
        $js_prod_item->variantId = $variation_id;

        $name = $item->getName();
        $ss_prod_item->name = $name;
        $js_prod_item->productName = $name;

        $quantity = (int) $item->getQtyOrdered();
        $ss_prod_item->quantity = $quantity;
        $js_prod_item->quantity = $quantity;

        $price = (float) $item->getPrice();
        $ss_prod_item->price = $price;
        $js_prod_item->price = $price;

        $ss_products_array[] = $ss_prod_item;
        $js_products_array[] = $js_prod_item;
    }

    //create json object for server-side
    $ss_obj = new stdClass();
    $ss_obj->products = $ss_products_array;

    //Set Customer Shipping Data
    $customer_shipping = new stdClass();
    $customer_shipping->address1 = $address1;
    $customer_shipping->address2 = $address2;
    $customer_shipping->city = $city;
    $customer_shipping->state = $state;
    $customer_shipping->zip = $zip;
    $customer_shipping->country_code = $country_code;

    $ss_obj->customer_shipping_address = $customer_shipping;

    //Set Order Data
    $ss_obj->order_id = $order_id;
    $ss_obj->customer_id = $customer_id;
    $ss_obj->time_of_purchase = $time_of_purchase;
    $ss_obj->customer_email = $customer_email;
    $ss_obj->customer_phone_number = $customer_phone_number;
    $ss_obj->customer_name = $customer_name;
    $ss_obj->customer_ip_address = $customer_ip_address;
    $ss_obj->discount_amount = (float) $order-> getDiscountAmount();
    $ss_obj->tax = $tax;
    $ss_obj->currency = $currency;
    $ss_obj->purchase_total = $purchase_total;
    if($coupon_code) {
        $ss_obj->discount_codes = array($coupon_code);
    }

    $post_data = json_encode($ss_obj);

    //Create json object for javascript firePurchaseEvent
    $js_obj = new stdClass();

    $js_obj->id = $order_id;
    $js_obj->totalPrice = $purchase_total;
    $js_obj->shippingPrice = $shipping_total;
    $js_obj->taxPrice = $tax;
    if ($coupon_code) {
        $js_obj->coupons = $coupon_code;
    }
    $js_obj->currency = $currency;
    $js_obj->customerId = $customer_id;
    $js_obj->lineItems = $js_products_array;

    //convert to json format
    $post_data = json_encode(array($ss_obj));
    $js_data = json_encode($js_obj);

    //create return object
    $api_object = new stdClass();
    $api_object->server_object = $post_data;
    $api_object->javascript_object = $js_data;

    return $api_object;
}