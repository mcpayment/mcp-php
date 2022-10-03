<?php

/**
 * CreateTransaction.php
 * php version 7.2.0
 *
 * @category Class
 * @package  Xendit
 * @author   Ellen <ellen@xendit.co>
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     https://api.xendit.co
 */

namespace MCPhp;

class CreateTransaction
{
    use Api\Request;
    use Api\GenerateLink;


    public static function createUrl()
    {
        return "/payment-page/payment";
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
            'amount',
            'description',
            'is_customer_paying_fee',
            'customer_details',
            'item_details',
            'selected_channels',
            'billing_address',
            'shipping_address',
            'save_card',
            'callback_url',
            'success_redirect_url',
            'failed_redirect_url'
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

    /**
     * Get available VA banks
     *
     * @return array[
     * 'name' => string,
     * 'code' => string
     * ]
     * @throws Exceptions\ApiException
     */
    public static function getVABanks()
    {
        $url = '/available_virtual_account_banks';

        return static::_request('GET', $url);
    }

    /**
     * Get FVA payment
     *
     * @param string $payment_id payment ID
     *
     * @return array[
     * 'id'=> string,
     * 'payment_id'=> string,
     * 'callback_virtual_account_id'=> string,
     * 'external_id'=> string,
     * 'merchant_code'=> string,
     * 'account_number'=> string,
     * 'bank_code'=> string,
     * 'amount'=> int,
     * 'transaction_timestamp'=> string
     * ]
     * @throws Exceptions\ApiException
     */
    public static function getFVAPayment($payment_id)
    {
        $url = '/callback_virtual_account_payments/payment_id=' . $payment_id;

        return static::_request('GET', $url);
    }
}
