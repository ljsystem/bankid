# PHP package to integrate with Swedish BankID

## Install

```
composer require ljsystem/bankid
```

## Certificates

Certificates for the BankID test-environment is provided in the package and will be used by default if no arguments are provided:

```php
$bankId = new BankID();
```

Certificate and key for the production environment usually comes in two files, `.cer` and `.key`. They could also be bundled together in one file, usually ending in `.p12` or `.pfx`. The key is protected with a passphrase.

The CA certificate for the production environment can be found in the BankID documentation at https://www.bankid.com/bankid-i-dina-tjanster/rp-info.

Here is an example of using `.cer` and `.key`, in this example named `prod.cer` and `prod.key`:

```php
$bankId = new BankID('prod', '/path/to/prod.cer', '/path/to/prod_cacert.cer', '/path/to/prod.key', 'key-passphrase');
```

To use a single file, convert the `p12/pfx`-file to a PEM-encoded file:

```bash
openssl pkcs12 -in prod.p12  -out prod.pem
```

And here is how to use it together with a passphrase:

```php
$bankId = new BankID('prod', '/path/to/prod.pem', '/path/to/prod_cacert.cer', null, 'key-passphrase');
```

## Security

If you discover any security related issues, please contact security@ljsystem.se instead of using the issue tracker.
