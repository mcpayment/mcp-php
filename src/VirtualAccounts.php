<?php

/**
 * VirtualAccounts.php
 * php version 7.2.0
 *
 * @category Class
 * @package  MCPhp VirtualAccounts
 * @author   Mahendra <mahendra@mcpayment.co.id>
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     https://developer.mcpayment.id
 */

namespace MCPhp;

class VirtualAccounts
{
    use Api\Request;
    use Api\VA\Create;
    use Api\VA\Payment;
    use Api\VA\Cancel;
    use Api\VA\Inquiry;


    public static function createUrlVA()
    {
        return "/va";
    }

    public static function payUrlVA(string $host)
    {
        switch ($host) {
            case 'PERMATA':
                $url = '/va-permata/payment';
                break;
            
            case 'BCA': //BCA-ALTO
                $url = '/va-bca-alto/payment';
                break;

            case 'BCA-MCP': //BCA-MCP
            case 'BCA-POLYTRON': //BCA-POLYTRON
                $url = '/va-bca-aggregator/payment';
                break;
                
            case 'BCA-MOLA': //BCA-MOLA
                $url = '/va-bca/payment';
                break;
                
            case 'BRI': //BRI
                $url = '/va-bri/payment';
                break;
                
            case 'BNC': //BNC
            case 'BNC-BLIBLI': //BNC-BLIBLI
                $url = '/va-bnc/payment';
                break;

            default:
                # code...
                break;
        }

        return $url;
    }

    public static function cancelUrlVA()
    {
        return "/va/cancel";
    }

    public static function inquiryUrlVA()
    {
        return "/va/inquiry";
    }

    /**
     * Instantiate required params for Create
     *
     * @return array
     */
    public static function createReqParams()
    {
        return [
            'external_id',
            'order_id',
            'payment_method',
            'payment_channel',
            'currency',
            'payment_details',
            'customer_details',
            'item_details',
            'shipping_address',
            'billing_address',
            'payment_options',
            'additional_data',
            'callback_url'
        ];
    }

    /**
     * Instantiate required params for Update
     *
     * @return array
     */
    public static function updateReqParams()
    {
        return [];
    }

}
