<?php

/**
 * Inquiry.php
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

trait Inquiry
{
    /**
     * Send a create request
     *
     * @param array $params user's params
     *
     * @return array
     */
    public static function inquiry($headers, $params = [])
    {
        Mcp::setApiEnv(env('MCP_ENV'));
        $apiEnv = Mcp::getApiBase();
        $url = static::inquiryUrlVA();
        $headers = Mcp::getHeaders();
        $body = json_encode($params);

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
