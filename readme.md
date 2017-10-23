# Short library to support certified signature of documents provided by CertEurope

## Usage example :

```php
$signator = Signator::createFromYamlFile('/path/to/config.yml');
//$signator = Signator::createFromYaml($yamlObject);
```

```php
$signator = Signator::createFromYaml($yamlObject);

$smsSent = $signator->sendAuthenticationRequest('0601020304');

$validated = $signator->checkAuthenticationRequest('0601020304', '123456');
```

```php
$signator = Signator::createFromYaml($yamlObject);

$signature = Signature::create()->setImage('/path/to/sign.png', false)->setText('Signature label');
//$signature = Signature::create()->setImage('BASE64')->setText('Signature label');

$request = Request::create()
    ->addHolder('Firstname', 'Lastname', 'certisign@ylly.fr', '0601020304')
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

$signator = Signator::createFromYaml($yamlObject);

$signator->addListener(new Listener());
```