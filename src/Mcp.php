<?php

namespace MCPhp;

use GuzzleHttp\Client as Guzzle;

class Mcp
{
    public static $apiBase, $merchantId, $hashKey, $secretUnboundId, $headers;
    const VERSION = "1.0.0";

    /**
     * ApiBase getter
     *
     * @return string
     */
    public static function getApiBase(): string
    {
        return self::$apiBase;
    }

    /**
     * ApiBase setter
     *
     * @param string $apiBase api base url
     *
     * @return void
     */
    public static function setApiEnv(string $apiEnv): void
    {
        switch ($apiEnv) {
            case 'live':
                $base = 'https://api-live.mcpayment.id';
                break;

            case 'stage':
                $base = 'https://api-stage.mcpayment.id';
                break;

            default:
                $base = 'https://api-dev.mcpayment.id:5443/v2';
                break;
        }
        self::$apiBase = $base;
    }

    /**
     * Get the array of headers
     *
     * @return void
     */
    public static function setHeaders($externalId, $orderId)
    {
        $headers = [
            'Content-Type' => 'application/json',
            'Authorization' => 'Basic ' . base64_encode(self::$merchantId . ':' . self::$secretUnboundId),
            'x-req-signature' => hash('sha256', self::$hashKey . $externalId . $orderId),
            'x-version' => 'v3',
        ];
        self::$headers = $headers;
    }

    /**
     * Get the value of Headers
     *
     * @return array Headers
     */
    public static function getHeaders()
    {
        return self::$headers;
    }

    /**
     * Get the value of merchant ID
     *
     * @return string Merchant Index
     */
    public static function getMerchantId()
    {
        return self::$merchantId;
    }

    /**
     * Get the value of hashKey
     *
     * @return string Secret Hash key
     */
    public static function getHashKey()
    {
        return self::$hashKey;
    }

    /**
     * Get the value of secretUnboundId
     *
     * @return string Secret API key
     */
    public static function getSecretUnboundId()
    {
        return self::$secretUnboundId;
    }

    /**
     * Set the value of merchant ID
     *
     * @param string $merchantId Merchant Index
     *
     * @return void
     */
    public static function setMerchantId($merchantId)
    {
        self::$merchantId = $merchantId;
    }

    /**
     * Set the value of hashKey
     *
     * @param string $hashKey Secret hash key
     *
     * @return void
     */
    public static function setHashKey($hashKey)
    {
        self::$hashKey = $hashKey;
    }

    /**
     * Set the value of secretUnboundId
     *
     * @param string $secretUnboundId Secret Unbound ID
     *
     * @return void
     */
    public static function setSecretUnboundId($secretUnboundId)
    {
        self::$secretUnboundId = $secretUnboundId;
    }

    /**
     * Send access_token request
     * 
     * @return string
     */
    public static function getTokenAccess() 
    {
        $client = new Guzzle();
        $request = $client->post(
            self::$apiBase . '/va-bca/api/oauth/token', 
            [
                'headers' => [
                    'Content-Type' => 'application/x-www-form-urlencoded',
                    'Authorization' => 'Basic ' . env('AUTH_TOKEN_BCA')
                ],
                'form_params' => [
                    'grant_type' => 'client_credentials'
                ]
            ],
        );
        $res = json_decode($request->getBody()->getContents(), true);
        
        return $res['access_token'];
    }

    /**
     * Get the company ID
     * 
     * @param string $channel   Payment channel / bank
     * @param string $mode      Transaction mode: CLOSE/OPEN/INSTALLMENT
     * @return object
     */
    public static function getCompanyCode($channel, $mode)
    {
        $mode = strtoupper($mode);

        if (env('MCP_ENV') == 'stage') {
            switch ($channel) {
                case 'BCA-MOLA':
                    $binCode = '00001';
                    $subCode = '0000377';
                    break;
                
                case 'BCA-POLYTRON':
                    $binCode = '38137';
                    $subCode = '00001';
                    break;
                    
                case 'BCA-MCP':
                    $binCode = ($mode == 'PARTIAL') ? '40415' : '40416';
                    if ($mode == 'OPEN') { $binCode = '40414'; }
                    
                    $subCode = '00001';
                    break;
                    
                default:
                    $companyCode = '00001';
                    break;
            }

        } else {
            switch ($channel) {
                case 'BCA-MOLA':
                    $binCode = '38137';
                    $subCode = '00001';
                    break;
                
                case 'BCA-POLYTRON':
                    $binCode = '38137';
                    $subCode = '00001';
                    break;
                    
                case 'BCA-MCP':
                    $binCode = ($mode == 'INSTALLMENT') ? '40415' : '40416';
                    if ($mode == 'OPEN') { $binCode = '40414'; }
                    
                    $subCode = '00001';
                    break;
                    
                default:
                    $companyCode = '00001';
                    break;
            }
        }

        $code = [
            'binCode' => $binCode,
            'subCode' => $subCode
        ];
        return (object) $code;
    }
}
