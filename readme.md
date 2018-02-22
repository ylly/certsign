# CertSign PHP library

This library allows you to easily implement CertSign by CertEurope into your project.

[![Build Status](https://travis-ci.org/ylly/certsign.svg?branch=master)](https://travis-ci.org/ylly/certsign)

## Require :

* PHP 5.4+
* PHP GD
* PHP compiled with FreeType support, else the provided image generation service will fallback to standard text

## Limitations :

* Only SYNC sign mode is implemented

## Installation :

```
composer require ylly/certsign
```

## Usage :

### Create a signator

The signator manage authentication and signature

You can create a signator from a YAML config file
```php
$signator = SignatorFactory::createFromYamlFile('/path/to/config.yml');
```

Or from an key-value array of configuration
```php
$signator = SignatorFactory::createFromArray($configArray);
```

### Fill user informations and documents

The users informations and documents are stored in a signature request

```php
$signature = Signature::create()->setImage('/path/to/sign.png', false);
//$signature = Signature::create()->setImage('BASE64');
//$signature = Signature::create()->setImage(new Image(...));

$request = Request::create()
    ->setHolder('Firstname', 'Lastname', 'certsign@ylly.fr', '0601020304')
    ->addDocument('Document-1', '/path/to/doc.pdf', $signature, false)
    ->addDocument('Document-2', 'BASE64', $signature);
```

### Sign documents

You have two ways to sign documents, with or without authentication

The authentication can be handled by email or SMS

#### With authentication (OTP)

```php
$request->setOTP('0601020304'); // Will send a SMS
//$request->setOTP('certsign@ylly.fr'); // Will send an Email

$orderId = $signator->create($request);

// Send the OTP, can be reused to generate a new OTP
$signator->validate($orderId);

// Enter OTP given by SMS or Email, will return false if the code is invalid
$documents = $signator->sign($orderId, 'MyOTP');
```

#### Without authentication (Direct sign)

```php
$orderId = $signator->create($request);

$documents = $signator->sign($orderId);
```

## Configuration file :

```yaml
env: test # or prod
cert: /etc/ssl/certsign.pem
cert_password: password
proxy: locahost:8080 # optionnal web proxy
```

## Advanced usage :

A Log interface is provided to manage outputed logs, you can register your listener on the signator

```php
class Listener implement LogListenerInterface
{
    public function recieve($level, $message)
    {
        // do something
    }
}

$signator->addListener(new Listener());
```

Instead of using a static image, you can generate a simple image using the following scripts :
```php
$image = new Image(100, 50, new Color(255, 255, 255));
$image->setStyle(new TextStyle(0, 0, 12, 2, new Color(0, 0, 0));
$image->addText('SignText');
```