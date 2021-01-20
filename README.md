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
$bankId = new BankID('prod', '/path/to/prod.cer', '/path/to/prod_cacert.cer', '/path/to/prod.key', 'key-passphrase', '5.1');
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

## Bank ID version

By default when you create a new instace of a BankID you can pass several parameters. One the the parameters is to decide the version to use of the Bank ID API. Default value (at the time of this writing is v5.1).

## Bank ID versions
Ver.    Release    End of life
==============================
v4      2014-01     2019-03
v4*     2017-03     2020-02 *v4 was released again with updated CA, a new endpoint and required TLS1.1 or TLS1.2
v5      2018-02     2022-04
v5.1    2020-04     -       *Support for  animated QR, autoStartTokenRquired deprecated. tokenStartRequired introduced.

To read more about the different versions,
https://www.bankid.com/assets/bankid/rp/bankid-relying-party-guidelines-v3.5.pdf
or
https://www.bankid.com/utvecklare/rp-info
