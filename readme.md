# Short library to support certified signature of documents provided by CertEurope

## Usage example :

```php
$signator = new Signator('test', '/path/to/cert.pem', 'cert_password', 'sms_api_key', 'sms_return_url');

$smsSent = $signator->sendAuthentificationRequest('0601020304');

$validated = $signator->checkAuthentificationRequest('0601020304', '123456');

$request = Request::create()
    ->addHolder('Firstname', 'Lastname', 'certisign@ylly.fr', '0601020304')
    ->addDocument('Document-1', '/path/to/doc.pdf', false)
    ->addDocument('Document-2', '/path/to/doc.pdf', false);

$signInfo = new Signature('/path/to/sign.png', 'Signature label');

$documents = $signator->signDocuments($request, $signInfo);
```