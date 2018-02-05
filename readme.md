# CertSign PHP library

This library allows you to easily implement CertSign by CertEurope into your project.

[![Build Status](https://travis-ci.org/ylly/certsign.svg?branch=master)](https://travis-ci.org/ylly/certsign)

## Require :

* PHP 5.4+
* PHP GD
* FreeType

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

### Authenticate user

To authenticate the user, you can use these methods to send and verifiy a code sent by SMS
```php
$smsSent = $signator->sendAuthenticationRequest('0601020304');
$validated = $signator->checkAuthenticationRequest('0601020304', '123456');
```

### Sign document

Once the user is verified, you can generate the request with a signature and send it to CertSign, if everything is valid, you will recieve a list of signed documents with base64 encoded content
```php
$signature = Signature::create()->setImage('/path/to/sign.png', false);
//$signature = Signature::create()->setImage('BASE64');
//$signature = Signature::create()->setImage(new Image(...));

$request = Request::create()
    ->setHolder('Firstname', 'Lastname', 'certisign@ylly.fr', '0601020304')
    ->addDocument('Document-1', '/path/to/doc.pdf', $signature, false)
    ->addDocument('Document-2', 'BASE64', $signature);

$documents = $signator->signDocuments($request);
```

## Configuration file :

```yaml
env: test # or prod
cert: /etc/ssl/certisign.pem
cert_password: password
api_key: 123456
api_endpoint: https://www.ylly.fr
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