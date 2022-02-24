These testing certs are provided here for convinience. They can be downloaded from https://www.bankid.com. Note that they have to be downloaded again when they expire.

To convert the downloaded `.p12`-file use the following command:

```bash
openssl pkcs12 -nodes -in DOWLOADED_CERT.p12 -out test.pem
```
