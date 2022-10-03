<?php

/**
 * GenerateTransactionExample.php
 * php version 7.2.0
 *
 * @category Example
 * @package  MCPhp Examples
 * @author   Mahendra <mahendra@mcpayment.co.id>
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     https://developer.mcpayment.id
 */

use MCPhp\Mcp;
use MCPhp\CreateTransaction;

require 'vendor/autoload.php';

class GenerateTransactionExample {
    
    /**
     * Setup the headers and body's request
     * 
     * @param array $params    Request's body from generateLinkVA(), generateEMoney(), generateLinkCard()
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
        

        // Headers get should be after setting above
        $headers = Mcp::getHeaders();
        $body = [
            'order_id' => $params->orderId,
            'external_id' => $params->externalId,
            'amount' => $params->amount,
            'description' => $params->description,
            'is_customer_paying_fee' => false,
            'customer_details' => array(
                'full_name' => 'Lib Test Name',
                'email' => 'test.lib@testing.co',
                'phone' => '0000000000',
                'address' => 'Jl. Raya Pasar Minggu'
            ),
            'item_details' => [
                array(
                    'item_id' => 'ID 12',
                    'name' => 'item 1',
                    'amount' => $params->amount,
                    'qty' => 1,
                    'description' => 'item description',
                ),
                // More item_details here
            ],
            'selected_channels' => $params->selected_channels,
            'shipping_address' => array(
                'full_name' => 'Shipper Name',
                'phone' => '089876543210',
                'address' => 'Jl. Warung Jati Barat 8a ',
                'city' => 'Jakarta',
                'postal_code' => '11111',
                'country' => 'ID',
            ),
            'billing_address' => array(
                'full_name' => 'Customer Billed Name',
                'phone' => '081234567890',
                'address' => 'Jl. Jalan 8a ',
                'city' => 'Jakarta',
                'postal_code' => '00001',
                'country' => 'ID',
            ),
            'save_card' => false,
            'callback_url' => 'https://mcpid.proxy.beeceptor.com',
            'success_redirect_url' => 'https://beeceptor.com/console/mcpid',
            'failed_redirect_url' => 'https://beeceptor.com/console/mcpid',
            'expired_time' => '' //not mandatory
        ];


        return var_dump((array) CreateTransaction::generateLink($headers, $body));
    }


    /**
     * For more supported bank/payment channel, 
     * please visit https://developer.mcpayment.id
     * 
     */
    public static function generateLinkVA() {
        
        $params = [
            'orderId'           => 'LibTestOrder-0' . rand(1, 99),
            'externalId'        => strtoupper(md5(date('dMYHis'))),
            'amount'            => 10000,
            'description'       => 'This is order/transaction description VA',
            'selected_channels' => [
                array(
                    'channel' => 'VA',
                    'acq' => 'PERMATA', //for more supported bank, please visit https://developer.mcpayment.id
                    'payment_system' => 'CLOSED',
                    'is_multi_use' => false
                )
            ],
        ];

        self::setupParams((object) $params);
    }

    /**
     * For more supported bank/payment channel, 
     * please visit https://developer.mcpayment.id
     * 
     * @param string $channel   E-Money payment channel or bank
     * 
     */
    public static function generateLinkEMoney($channel) {
        
        if (!in_array(strtoupper($channel), ['OVO','DANA','SHOPEEPAY'])) {
            return var_dump([
                'status' => false, 
                'message' => 'E-money channel not supported.', 
                'http_code' => http_response_code(400)
            ]);
        }

        $params = [
            'orderId'           => 'LibTestOrder-0' . rand(1, 99),
            'externalId'        => strtoupper(md5(date('dMYHis'))),
            'amount'            => 10000,
            'description'       => 'This is order/transaction description Wallet',
            'selected_channels' => [
                array(
                    'channel' => strtoupper($channel), //for more supported channels, please visit https://developer.mcpayment.id
                )
            ],
        ];

        self::setupParams((object) $params);
    }

    /**
     * For more supported bank/payment channel, 
     * please visit https://developer.mcpayment.id
     * 
     */
    public static function generateLinkCard() {
        
        $params = [
            'orderId'           => 'LibTestOrder-0' . rand(1, 99),
            'externalId'        => strtoupper(md5(date('dMYHis'))),
            'amount'            => 10000,
            'description'       => 'This is order/transaction description Wallet',
            'selected_channels' => [
                array(
                    'channel' => 'CARD',
                    'acq' => 'BCACC', //for more supported bank, please visit https://developer.mcpayment.id
                    'payment_system' => 'CLOSED',
                    'is_multi_use' => false
                )
            ],
        ];

        self::setupParams((object) $params);
    }
}

