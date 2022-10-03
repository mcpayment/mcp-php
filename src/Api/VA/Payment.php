<?php

/**
 * Payment.php
 * php version 7.2.0
 *
 * @category Trait
 * @package  MCPhp
 * @author   Mahendra <mahendra@mcpayment.co.id>
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     https://developer.mcpayment.id
 */

namespace MCPhp\Api\VA;

use GuzzleHttp\Client as Guzzle;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Request;
use MCPhp\Mcp;
use Psr\Http\Message\ResponseInterface;

trait Payment
{
    /**
     * Send a Payment request
     *
     * @param array $params     Request's body
     *
     * @return array
     */
    public static function pay($params = [])
    {
        Mcp::setApiEnv(env('MCP_ENV'));
        $apiEnv = Mcp::getApiBase();
        $host = $params['host'];
        $url = static::payUrlVA($host);
        $headers = ['Content-Type' => 'application/json'];
        
        switch ($host) {
            case 'PERMATA':
                $headers['api-key'] = env('MCP_VA_API_KEY');
                $body = [
                    'PayBillRq' => array(
                        'INSTCODE' => '7999',
                        'VI_VANUMBER' => $params['va_number'],
                        'VI_TRACENO' => time(),
                        'VI_TRNDATE' => date('c', time()),
                        'BILL_AMOUNT' => $params['amount'],
                        'VI_CCY' => '360',
                        'VI_DELCHANNEL' => '099'
                    )
                ];
                break;
            
            case 'BCA': //BCA-ALTO
                $custNum = substr($params['va_number'], 7);
                $body = [
                    'command' => 'payment',
                    'data' => array(
                        'bank_code' => '014',
                        'customer_number' => $custNum,
                        'reference_number' => rand(1, 999999999999),
                        'currency_code' => 'IDR',
                        'virtual_account_number' => $params['va_number'],
                        'bill_list' => [ 
                            array(
                                'bill_number' => '01',
                                'bill_amount' => $params['amount'],
                                'payment_reference' => time(),
                            )
                        ],
                        'pg_code' => '20',
                        'date_time' => date('c', time()),
                        'total_amount' => $params['amount'],
                        'paid_amount' => $params['amount'],
                        'customer_name' => 'Sebuah Nama',
                        'channel_type' => '6014',
                        'request_id' => rand(1, 99999999)
                    )                    
                ];
                
                $bodyString = json_encode($body);
                $altoKey = env('MCP_ALTO_KEY');
                $validationKey = env('MCP_VALIDATION_KEY');
                $timestamp = date('c', time());

                $headers['X-Alto-Signature'] = hash_hmac(
                    'sha256',
                    'POST:/va-bca-alto/payment:'.$altoKey.':'.hash('sha256',$bodyString).':'.$timestamp,
                    $validationKey);
                $headers['X-ALto-Key'] = $altoKey;
                $headers['X-Alto-Timestamp'] = $timestamp;
                $headers['Accept-encoding'] = 'identity';
                $headers['Accept-language'] = 'en-US, id-ID';
                $headers['Connection'] = 'close';
                $headers['User-agent'] = 'ALTOPay Virtual Account version 1.1';

                break;

            case 'BCA-MCP': //BCA-MCP
            case 'BCA-MOLA': //BCA-POLYTRON
            case 'BCA-POLYTRON': //BCA-POLYTRON
                if ($host == 'BCA-MOLA') {
                    $headers['Authorization'] = 'Bearer ' . Mcp::getTokenAccess();
                }
                $code = Mcp::getCompanyCode($host, $params['mode']);

                $body = [
                    'CompanyCode' => $code->binCode,
                    'CustomerNumber' => substr($params['va_number'], 5),
                    'RequestID' => rand(1, 9999999999),
                    'ChannelType' => '6014',
                    'CustomerName' => $params['customer_name'], 
                    'CurrencyCode' => 'IDR',
                    'PaidAmount' => $params['amount'],
                    'TotalAmount' => $params['amount'],
                    'SubCompany' => $code->subCode,
                    'TransactionDate' => date('d/m/Y H:i:s'), 
                    'Reference' => time(),
                    'DetailBills' => [],
                    'FlagAdvide' => 'N',
                    'Additionaldata' => ''
                ];
                break;
                
            case 'BRI':
                $body = [
                    'IdApp' => 'TEST',
                    'PassApp' => 'TEST',
                    'TransmisiDateTime' => date('YmdHis'),
                    'BankID' => '002',
                    'TerminalID' => '1',
                    'BrivaNum' => (string) $params['va_number'],
                    'PaymentAmount' => $params['amount'],
                    'TransaksiID' => time()
                ];
                break;
            case 'BNC':
            case 'BNC-BLIBLI':
                $hit = new Guzzle(['base_uri' => $apiEnv]);
                $resp = $hit->post('/va-bnc/inquiry', ['json' => ['transaction_id' => $params['trxId']]])->getBody()->getContents();
                $traxId = json_decode($resp);

                $body = [
                    'orderNo' => $params['orderId'],
                    'orderStatus' => 2,
                    'notityType' => 1,
                    'traxId' => $traxId->data->traxId,
                    'tanxTime' => date('Y-m-d H:i:s'),
                    'totalAmount' => $params['amount'],
                    'PayBankName' => 'BNC',
                    'payACNo' => '8888'
                ];
                break;
                
            default:
                # code...
                break;
        }

        $body = json_encode($body);

        $client = new Guzzle();
        $request = new Request('POST', $apiEnv . $url, $headers, $body);
        $promise = $client->sendAsync($request);
        
        $promise->then(
            function (ResponseInterface $res) {
                $response = json_decode($res->getBody()->getContents());
                return $response;
            },
            function (RequestException $e) {
                $response = [];
                $response->data = $e->getMessage();
                return $response;
            }
        );
        $response = $promise->wait();
        $resp = json_decode($response->getBody(), false);
        
        return $resp;

    }
}
