<?php

    $spBaseUrl = 'http://www.spotlighthometours.com'; //or http://<your_domain>

    $settingsInfo = array (
        'sp' => array (
            'entityId' => $spBaseUrl.'/php-saml-2.12.0/metadata.php',
            'assertionConsumerService' => array (
                'url' => $spBaseUrl.'/php-saml-2.12.0/index.php?acs',
            ),
            'singleLogoutService' => array (
                'url' => $spBaseUrl.'/php-saml-2.12.0/index.php?sls',
            ),
            'NameIDFormat' => 'urn:oasis:names:tc:SAML:1.1:nameid-format:unspecified',
        ),
        'idp' => array (
            'entityId' => 'https://app.onelogin.com/saml/metadata/727210',
            'singleSignOnService' => array (
                'url' => 'https://spotlighthometours.onelogin.com/trust/saml2/http-post/sso/727210',
            ),
            'singleLogoutService' => array (
                'url' => 'https://spotlighthometours.onelogin.com/trust/saml2/http-redirect/slo/727210',
            ),
            'x509cert' => '-----BEGIN CERTIFICATE-----
MIIEODCCAyCgAwIBAgIUSbSkgxKnfP0eOgAugAwAyFHHFkIwDQYJKoZIhvcNAQEF
BQAwYzELMAkGA1UEBhMCVVMxGzAZBgNVBAoMEnNwb3RsaWdodGhvbWV0b3VyczEV
MBMGA1UECwwMT25lTG9naW4gSWRQMSAwHgYDVQQDDBdPbmVMb2dpbiBBY2NvdW50
IDExODA1NzAeFw0xNzExMjAxNzQ1NTNaFw0yMjExMjExNzQ1NTNaMGMxCzAJBgNV
BAYTAlVTMRswGQYDVQQKDBJzcG90bGlnaHRob21ldG91cnMxFTATBgNVBAsMDE9u
ZUxvZ2luIElkUDEgMB4GA1UEAwwXT25lTG9naW4gQWNjb3VudCAxMTgwNTcwggEi
MA0GCSqGSIb3DQEBAQUAA4IBDwAwggEKAoIBAQDBr9S7vYEa8d4aVP+QzlzgYTHy
ZPXJ+CnTIWycSHzjDujBjEK93skbddnp8cq2Kh9JVuySQT/sHBoDx589JRKMRV89
9JKSqGl/5aQipRlcOLOhNu6GvGTttcG40X4rYKJvJB0OgFrl0pK7IHpx1KfPUXUF
cjQSuFImlL+gnAM7uy8YGUaJ0L9N9sp/kOgHpbRNSnY44mlo+Z99UgWhD8/hJExc
iZ33GISffpAXh7AatXNzgEeHnJojN2nhWGnN50Kk5GZ8jThzh9X6x76B4lHNXKSF
uCMgkTdAbTreUau0+2GPjTtKd7bQ4TOAdm376cIXUGzUid3XABT+XSsDECh1AgMB
AAGjgeMwgeAwDAYDVR0TAQH/BAIwADAdBgNVHQ4EFgQU5PqrNjRdseOH36KstVdc
WOHOY0owgaAGA1UdIwSBmDCBlYAU5PqrNjRdseOH36KstVdcWOHOY0qhZ6RlMGMx
CzAJBgNVBAYTAlVTMRswGQYDVQQKDBJzcG90bGlnaHRob21ldG91cnMxFTATBgNV
BAsMDE9uZUxvZ2luIElkUDEgMB4GA1UEAwwXT25lTG9naW4gQWNjb3VudCAxMTgw
NTeCFEm0pIMSp3z9HjoALoAMAMhRxxZCMA4GA1UdDwEB/wQEAwIHgDANBgkqhkiG
9w0BAQUFAAOCAQEANj27PZuMps7aOyiB5DVvU1AB8ukXfspNW/dq6CXOeU01V0z+
WRxWIns1+qJnYyANCVHfc0fqpwYMMW9/q9Kez35HP6FtwFYKsogsgoMa1OtNiblY
ISvLAvR8hOiMueEGnE7knkWsXeDjDKmKwDpJe0JumjXuQq7+gMpU9/GWbI3+KfPP
jAA6o8jEWyXOmKR4T6uaopgjyaDnbmBFUsoV4j9TH9HZlq7COeKdOt30Un5F2xfv
praTKLj1oeYusSE4+JBbxj3nenxVdjJIzDggkb0ExqYIaw6mgcmiBSLMY3W+TScQ
vq3ieBmRpEPQPQ1W1KVBq/HT2BRJebkWKubySQ==
-----END CERTIFICATE-----',
        ),
    );
