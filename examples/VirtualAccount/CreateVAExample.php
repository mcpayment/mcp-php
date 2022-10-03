<?php

/**
 * CreateVAExample.php
 * php version 7.2.0
 *
 * @category Example
 * @package  MCPhp Examples
 * @author   Mahendra <mahendra@mcpayment.co.id>
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     https://developer.mcpayment.id
 */

use MCPhp\Mcp;
use MCPhp\VirtualAccounts;

require 'vendor/autoload.php';

class CreateVAExample {
    
    /**
     * Setup the headers and body's request
     * 
     * @param array $params    Request's body from generateVA()
     * 
     * @return array
     *  */
    protected static function setupParams($params) {

        // These can be set from env
        Mcp::setApiEnv(env('MCP_ENV'));
        Mcp::setMerchantId(env('MCP_MERCHANT_INDEX'));
        Mcp::setSecretUnboundId(env('MCP_SECRET_UNBOUND_ID'));
        Mcp::setHashKey(env('MCP_HASH_KEY'));
        Mcp::setHeaders($params->externalId, $params->orderId);
        
        $vaNumber = rand(1, 9999999);
        $multiUse = ($params->mode == 'SINGLE') ? false : true;
        switch ($params->mode) {
            case 'SINGLE':
            case 'MULTI':
                $mode = 'CLOSED';
                break;
            
            case 'OPEN':
                $mode = 'OPEN';
                break;

            case 'INSTALLMENT':
                $mode = 'INSTALLMENT';
                break;

            case 'PARTIAL':
                $mode = 'PARTIAL';
                break;

            default:
                return printf('Unknown type of mode.');
                break;
        }


        // Headers get should be after setting above
        $headers = Mcp::getHeaders();
        $body = [
            'external_id' => $params->externalId,
            'order_id' => $params->orderId,
            'payment_method' => 'bank_transfer',
            'payment_channel' => $params->payChannel,
            'currency' => 'IDR',
            'payment_details' => array(
                'billing_name' => 'Test Lib Package',
                'payment_system' => $mode,
                'is_multi_use' => $multiUse,
                // 'va_number' => ($multiUse) ? $vaNumber : '',
                'amount' => $params->amount,
                'is_customer_paying_fee' => false,
                'expired_time' => '',
                'transaction_description' => $params->description,
            ),
            'customer_details' => array(
                'email' => 'test.lib@testing.co',
                'full_name' => 'Lib Test Name',
                'phone' => '0000000000',
            ),
            'item_details' => $params->item_details,
            'shipping_address' => array(
                'full_name' => 'MC Payment',
                'phone' => '1+23456',
                'address' => 'Warung Jati 8a ',
                'city' => 'Jakarta',
                'postal_code' => '11111',
                'country' => 'Indonesia',
            ),
            'billing_address' => array(
                'full_name' => 'MC Payment',
                'phone' => '1+23456',
                'address' => 'Warung Jati 8a ',
                'city' => 'Jakarta',
                'postal_code' => '11111',
                'country' => 'Indonesia',
            ),
            'payment_options' => array(
                'referral_code' => 'agen 1',
                'promo_code' => '',
                'source' => 'payment_page',
            ),
            'additional_data' => 'some additional_data',
            'callback_url' => 'https://mcpid.proxy.beeceptor.com',
        ];
        // return var_dump($body);

        return var_dump((array) VirtualAccounts::create($headers, $body));
    }


    /**
     * For more supported bank/payment channel, 
     * please visit https://developer.mcpayment.id
     * 
     * @param string $payChannel    VA payment channel or bank
     * @param string $mode          VA mode (SINGLE/MULTI/OPEN/INSTALLMENT)
     * 
     */
    public static function generateVA($payChannel, $mode) {
        
        $params = [
            'mode'              => strtoupper($mode),
            'payChannel'        => strtoupper($payChannel),
            'orderId'           => 'LibTestOrder-0' . rand(1, 99),
            'externalId'        => strtoupper(md5(date('dMYHis'))),
            'amount'            => ($mode == 'PARTIAL') ? 20000 : 10000,
            'description'       => 'This is order/transaction description VA',
            'item_details'      => [
                array(
                    'item_id' => 'ID 12',
                    'name' => 'item 1',
                    'amount' => ($mode == 'PARTIAL') ? 20000 : 10000,
                    'qty' => 1,
                    'description' => 'item description',
                ),
                // it can be multiple item_details
            ],
        ];

        self::setupParams((object) $params);
    }

}

