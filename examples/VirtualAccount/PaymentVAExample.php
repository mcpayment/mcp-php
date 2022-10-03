<?php

/**
 * PaymentVAExample.php
 * php version 7.2.0
 *
 * @category Example
 * @package  MCPhp Examples
 * @author   Mahendra <mahendra@mcpayment.co.id>
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     https://developer.mcpayment.id
 */

use MCPhp\VirtualAccounts;

require 'vendor/autoload.php';

class PaymentVAExample {
    
    /**
     * Send Payment request for VA
     * 
     * @param string    $payChannel     VA payment channel or bank
     * @param integer   $vaNumber       VA number
     * @param integer   $vaNumber       VA amount of transaction
     * @param string    $mode           VA transaction's mode: CLOSED (SINGLE & MULTI), OPEN, INSTALLMENT
     * 
     * @return array
     */
    public static function paymentVA($payChannel, $vaNumber, $amount, $mode) {
        $params = [
            'host' => strtoupper(str_replace('_','-', $payChannel)),
            'mode' => strtoupper($mode),
            'va_number' => $vaNumber,
            'amount' => $amount,
            'customer_name' => 'Customer BCA Virtual Account'
        ];

        $payVA = (array) VirtualAccounts::pay($params);
        return var_dump($payVA);
    }


    /**
     * Payment for BNC and BNC-BLIBLI
     * 
     * @param string    $trxId      Transaction's ID
     * @param integer   $amount     Transaction's amount
     * @param string    $orderId    Transaction's order ID
     * @param boolean   $blibli     If the transaction is BNC-BLIBLI it should be true, otherwise it's false;
     * 
     * @return array
     */
    public static function paymentVABNC($trxId, $amount, $orderId, $blibli) {
        $params = [
            'host' => ($blibli) ? 'BNC-BLIBLI' : 'BNC',
            'trxId' => $trxId,
            'amount' => $amount,
            'orderId' => strtoupper($orderId)
        ];

        $payVA = (array) VirtualAccounts::pay($params);
        return var_dump($payVA);
    }

}

