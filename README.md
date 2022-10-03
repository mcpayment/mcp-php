MCP-PHP
===============

This is the Official PHP package for MCPayment API.
For more documentation available at [https://developer.mcpayment.id](https://developer.mcpayment.id).



## 1. Installation

Your application should meet minimum version of **PHP ≥ 7.2**.

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


## 2. How to Use

### General Settings

See `.env.example` for your env settings reference on your app. Then set it up for your headers's requests as below:
```
use MCPhp\Mcp;

Mcp::setApiEnv(MCP_ENV);
Mcp::setMerchantId(MCP_MERCHANT_INDEX);
Mcp::setSecretUnboundId(MCP_SECRET_UNBOUND_ID);
Mcp::setHashKey(MCP_HASH_KEY);
Mcp::setHeaders($externalId, $orderId);
```

## 3. Example
You can find the example at the `examples` folder
> See [documentation](https://developer.mcpayment.id) for details about `headers` and `body` requests.


### 3.a Create Transaction
Simply add this to your code:
```
use MCPhp\CreateTransaction;

CreateTransaction::generateLink($headers, $body));
```
<!-- the readme hasn't done -->
