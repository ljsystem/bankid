# PHP package to integrate with Swedish BankID

## Install

```
composer require ljsystem/bankid
```

## Version 1.0

Since version 6.0 of the [BankID API](https://www.bankid.com/utvecklare/guider/teknisk-integrationsguide/webbservice-api) introduced breaking changes so does the coresponding new version of this package, hence the version bump to version 1.0.

The major change is of course the removal of the inparameter for personal number to the methods `auth()` and `sign()`. These now requires that the authentication and signing process is initiated using animated QR codes. Read more on how to do this in the [BankID API documentation](https://www.bankid.com/utvecklare/guider/teknisk-integrationsguide/qrkoder).

Version 1.0 of this package also introduces the new methods with support for identification in phone calls, `phoneAuth()` and `phoneSign()`. **NOTE: These are as of now untested.**

## Certificates

### Test environment

Certificates for the BankID test-environment is provided in the package and will be used by default if no arguments are provided:

```php
$bankId = new BankID();
```

### Production environment

The CA certificate for the production environment can be found in the [BankID documentation](https://www.bankid.com/utvecklare/guider/teknisk-integrationsguide/miljoer) under "Issuer of server certificate".

Certificate and key for the production environment usually comes in two files, `.cer` and `.key`. The key is protected with a passphrase.
*Contact your company's bank to order these certificates.*

The certificates can also be bundled together in one file, usually ending in `.p12` or `.pfx`.

#### Two certificates usage

Here is an example of using `.cer` and `.key`, in this example named `prod.cer` and `prod.key`:

```php
$bankId = new BankID('prod', '/path/to/prod.cer', '/path/to/prod_cacert.cer', '/path/to/prod.key', 'key-passphrase');
```

#### Single file usage

To use a single file, convert the `p12/pfx`-file to a PEM-encoded file:

```bash
openssl pkcs12 -in prod.p12 -out prod.pem
```

And here is how to use it together with a passphrase:

```php
$bankId = new BankID('prod', '/path/to/prod.pem', '/path/to/prod_cacert.cer', null, 'key-passphrase');
```

## Security

If you discover any security related issues, please contact [samfundssystem@vitecsoftware.com](mailto:samfundssystem@vitecsoftware.com?subject=Report:%20Security%20issue%20in%20ljsystem/bankid) instead of using the issue tracker.
