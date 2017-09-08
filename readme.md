# Short library to support certified signature of documents provided by CertEurope

## Usage example :

```php
$signator = new Signator('test', '/path/to/cert.pem', 'cert_password', 'sms_api_key', 'sms_return_url');

$request = Request::create()
    ->addHolder('Mickaël', 'BLONDEAU', 'mickael@ylly.fr', '0601020304')
    ->addDocument('Test', '/path/to/sign.pdf', false)
    ->addDocument('Test2', '/path/to/sign.pdf', false);

$signInfo = new Signature('/path/to/sign.png', 'Signature label');

$documents = $signator->signDocuments($request, $signInfo);
```