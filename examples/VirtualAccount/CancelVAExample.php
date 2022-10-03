<?php

/**
 * CancelVA.php
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

class CancelVAExample {

    /**
     * Setup the headers and body's request
     * 
     * @param string $externalId    VA's external_id
     * @param string $orderId       VA's order_id
     * @param string $trxlId        VA's transaction_id
     * @param string $host          VA's payment channel/bank
     * 
     * @return array
     *  */
    public static function cancel($externalId, $orderId, $trxId, $host) {
        // These can be set from env
        Mcp::setMerchantId(env('MCP_MERCHANT_INDEX'));
        Mcp::setSecretUnboundId(env('MCP_SECRET_UNBOUND_ID'));
        Mcp::setHashKey(env('MCP_HASH_KEY'));
        Mcp::setHeaders($externalId, $orderId);

        // Headers get should be after setting above
        $headers = Mcp::getHeaders();
        $params = [
            'external_id' => $externalId,
            'order_id' => $orderId,
            'transaction_id' => $trxId,
            'payment_method' => 'BANK_TRANSFER',
            'payment_channel' => strtoupper($host)
        ];

        $cancelVA = (array) VirtualAccounts::cancel($headers, $params);

        return var_dump($cancelVA);
    }
}

