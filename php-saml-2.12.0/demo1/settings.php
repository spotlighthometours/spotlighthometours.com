<?php

    $spBaseUrl = 'http://www.spotlighthometours.com/'; //or http://<your_domain>

    $settingsInfo = array (
        'sp' => array (
            'entityId' => $spBaseUrl.'/php-saml-2.12.0/demo1/metadata.php',
            'assertionConsumerService' => array (
                'url' => $spBaseUrl.'/php-saml-2.12.0/demo1/index.php?acs',
            ),
            'singleLogoutService' => array (
                'url' => $spBaseUrl.'/php-saml-2.12.0/demo1/index.php?sls',
            ),
            'NameIDFormat' => 'urn:oasis:names:tc:SAML:1.1:nameid-format:unspecified',
        ),
        'idp' => array (
            'entityId' => 'https://app.onelogin.com/saml/metadata/726778',
            'singleSignOnService' => array (
                'url' => 'https://spotlighthometours.onelogin.com/trust/saml2/http-post/sso/726778',
            ),
            'singleLogoutService' => array (
                'url' => 'https://spotlighthometours.onelogin.com/trust/saml2/http-redirect/slo/726778',
            ),
            'x509cert' => '-----BEGIN CERTIFICATE-----
MIIEODCCAyCgAwIBAgIUFFFM7A9WgURtZYHvtQMageXuRhEwDQYJKoZIhvcNAQEF
BQAwYzELMAkGA1UEBhMCVVMxGzAZBgNVBAoMEnNwb3RsaWdodGhvbWV0b3VyczEV
MBMGA1UECwwMT25lTG9naW4gSWRQMSAwHgYDVQQDDBdPbmVMb2dpbiBBY2NvdW50
IDExNzkwOTAeFw0xNzExMTYyMjExMDlaFw0yMjExMTcyMjExMDlaMGMxCzAJBgNV
BAYTAlVTMRswGQYDVQQKDBJzcG90bGlnaHRob21ldG91cnMxFTATBgNVBAsMDE9u
ZUxvZ2luIElkUDEgMB4GA1UEAwwXT25lTG9naW4gQWNjb3VudCAxMTc5MDkwggEi
MA0GCSqGSIb3DQEBAQUAA4IBDwAwggEKAoIBAQDMyBFsG92SVgSIeTt4WpZ1Ti/i
hxGesJpej0W6zE8WpEbULOavNfZ0CAZf66W8HAwqUXY/ZVABRIchDVs5lgZX+FZ3
bkdUc8R8F0sL7bxOndWNom/QlwzltF0CFsNvSFWBfQi4n+4dPzjnAZVrZWzkJx+a
9IaZ2NnjpeG2IF4Uw/XJieI1Sw63GRoWHBNJdblCqYlRZZeLda4iQ0judg648+j/
ngnJxF6/UW14cyUwCZp2Jpk9giVIu7i44XR1JMoVDqZI+rq05/hh9unNSR9tOu1x
CRpDScq11va2rlG0Z/fAXxesApvWQOT2CUFMfQ2GeoFuTBgpyJbCpJVk+PSNAgMB
AAGjgeMwgeAwDAYDVR0TAQH/BAIwADAdBgNVHQ4EFgQUp+A9L/C7MjgHH6E35khe
mIj3Wc8wgaAGA1UdIwSBmDCBlYAUp+A9L/C7MjgHH6E35khemIj3Wc+hZ6RlMGMx
CzAJBgNVBAYTAlVTMRswGQYDVQQKDBJzcG90bGlnaHRob21ldG91cnMxFTATBgNV
BAsMDE9uZUxvZ2luIElkUDEgMB4GA1UEAwwXT25lTG9naW4gQWNjb3VudCAxMTc5
MDmCFBRRTOwPVoFEbWWB77UDGoHl7kYRMA4GA1UdDwEB/wQEAwIHgDANBgkqhkiG
9w0BAQUFAAOCAQEAWE+9DPbAbpAyFstk1EByVjaUT90VG4ra3j/ZgUF3VRg9vuLx
YwFQyammgayHAVG52iNxD5XHIc/RaeZrQPJojMWzn0YyA3BW3r3qTaWaOYDHwje8
uTHsMINhS2w7GsoDG2eiM3nRsECUl4OFo8z+8dOPn8/kRk1HcYP1mMa1pJ8oXwfl
wdm0kPa0aMmw/s1p5f5TMc4vCnYWJTZYleMtfSfp/OtQkCAuddViibvWLVi36kHX
0pVCVNH1wfb1G4q1SMUUSA4sam+SM0CPXE6tIRkU99b4sEoD8WN+61Lf3283+/rU
WTVFySwHkSbaPfTck5DLdyT9UDhhU9ReuPEBCw==
-----END CERTIFICATE-----',
        ),
    );
