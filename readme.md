# Short library to support certified signature of documents provided by CertEurope

## Usage example :

```php
$signator = SignatorFactory::createFromYamlFile('/path/to/config.yml');
//$signator = SignatorFactory::createFromArray($configArray);
```

```php
$signator = SignatorFactory::createFromArray($configArray);

$smsSent = $signator->sendAuthenticationRequest('0601020304');

$validated = $signator->checkAuthenticationRequest('0601020304', '123456');
```

```php
$signator = SignatorFactory::createFromArray($configArray);

$signature = Signature::create()->setImage('/path/to/sign.png', false);
//$signature = Signature::create()->setImage('BASE64');

$request = Request::create()
    ->setHolder('Firstname', 'Lastname', 'certisign@ylly.fr', '0601020304')
    ->addDocument('Document-1', '/path/to/doc.pdf', $signature, false)
    ->addDocument('Document-2', 'BASE64', $signature);

$documents = $signator->signDocuments($request);
```

## Configuration file :

```yaml
env: test
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

$signator = SignatorFactory::createFromArray($configArray);

$signator->addListener(new Listener());
```

Instead of using a static image, you can generate a simple image using the following scripts :
```php
$image = new Image(100, 50, new Color(255, 255, 255));
$image->setStyle(new TextStyle(0, 0, 12, 2, new Color(0, 0, 0));
$image->addText('SignText');
```