MCP-PHP
===============

This is the Official PHP package for MCPayment API.
For more documentation available at [https://developer.mcpayment.id](https://developer.mcpayment.id).  
  

# 1. Installation
Your application should meet minimum version of **PHP ≥ 7.2**.
> :bangbang: This package not ready yet for production use :bangbang:

If you are using [Composer](https://getcomposer.org), you can install via composer CLI:

```
composer require mcpayment/mcp-php
```

**or**

add this require line to your `composer.json` file:

```json
{
    "require": {
        "mcpayment/mcp-php": "1.*"
    }
}
```

and run `composer install` on your terminal.
  


# 2. How to Use

## General Settings

See `.env.example` for your env settings reference on your app. Then set it up for your headers's requests as below:
```
use MCPhp\Mcp;

Mcp::setApiEnv(MCP_ENV);
Mcp::setMerchantId(MCP_MERCHANT_INDEX);
Mcp::setSecretUnboundId(MCP_SECRET_UNBOUND_ID);
Mcp::setHashKey(MCP_HASH_KEY);
Mcp::setHeaders($externalId, $orderId);
```
  

## Generate Transaction Link
Generate the transaction using **CreateTransaction** trait, this will return an object.Please refer to this [Payment Page docs](https://developer.mcpayment.id/#e129bd57-6120-4a24-852f-ab2fb5bbfeef).
Simply add this to your code:
```
use MCPhp\CreateTransaction;

CreateTransaction::generateLink($headers, $body));
```
  

## Virtual Account
You can make `OPEN` and `PARTIAL` transaction's mode of virtual account with this. Simply add this to your code:
```
use MCPhp\VirtualAccounts;
```
  

### Create
```
VirtualAccounts::create($headers, $body));
```
### Payment
```
VirtualAccounts::pay($params);
```
### Cancel
```
VirtualAccounts::cancel($headers, $params);
```
### Inquiry
```
VirtualAccounts::inquiry($headers, $params);
```
  

  
## 3. Example
You can find the example at the [examples](https://github.com/mcpayment/mcp-php/tree/main/examples) folder
> See [documentation](https://developer.mcpayment.id) for details about `headers` and `body` requests.

<!-- the readme hasn't done -->
