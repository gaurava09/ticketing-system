<?php

/**
 * SAML 2.0 remote IdP metadata for SimpleSAMLphp.
 *
 * Remember to remove the IdPs you don't use from this file.
 *
 * See: https://simplesamlphp.org/docs/stable/simplesamlphp-reference-idp-remote
 */

$metadata['http://sts.mahindra.com/adfs/services/trust'] = [
    'entityid' => 'http://sts.mahindra.com/adfs/services/trust',
    'contacts' => [
        [
            'contactType' => 'support',
        ],
    ],
    'metadata-set' => 'saml20-idp-remote',
    'SingleSignOnService' => [
        [
            'Binding' => 'urn:oasis:names:tc:SAML:2.0:bindings:HTTP-Redirect',
            'Location' => 'https://sts.mahindra.com/adfs/ls/',
        ],
        [
            'Binding' => 'urn:oasis:names:tc:SAML:2.0:bindings:HTTP-POST',
            'Location' => 'https://sts.mahindra.com/adfs/ls/',
        ],
    ],
    'SingleLogoutService' => [
        [
            'Binding' => 'urn:oasis:names:tc:SAML:2.0:bindings:HTTP-Redirect',
            'Location' => 'https://sts.mahindra.com/adfs/ls/',
        ],
        [
            'Binding' => 'urn:oasis:names:tc:SAML:2.0:bindings:HTTP-POST',
            'Location' => 'https://sts.mahindra.com/adfs/ls/',
        ],
    ],
    'ArtifactResolutionService' => [
        [
            'Binding' => 'urn:oasis:names:tc:SAML:2.0:bindings:SOAP',
            'Location' => 'https://sts.mahindra.com/adfs/services/trust/artifactresolution',
            'index' => 0,
        ],
    ],
    'NameIDFormats' => [
        'urn:oasis:names:tc:SAML:1.1:nameid-format:emailAddress',
        'urn:oasis:names:tc:SAML:2.0:nameid-format:persistent',
        'urn:oasis:names:tc:SAML:2.0:nameid-format:transient',
    ],
    'keys' => [
        [
            'encryption' => true,
            'signing' => false,
            'type' => 'X509Certificate',
            'X509Certificate' => 'MIIC4jCCAcqgAwIBAgIQEk7IPEN+265AWBA2AH1vLzANBgkqhkiG9w0BAQsFADAtMSswKQYDVQQDEyJBREZTIEVuY3J5cHRpb24gLSBzdHMubWFoaW5kcmEuY29tMB4XDTIxMDQwNjA1NTEyMVoXDTIzMDQwNjA1NTEyMVowLTErMCkGA1UEAxMiQURGUyBFbmNyeXB0aW9uIC0gc3RzLm1haGluZHJhLmNvbTCCASIwDQYJKoZIhvcNAQEBBQADggEPADCCAQoCggEBAJcYjQvQK2Q81YwN2umoebWLvkU345HcjgllO8rb+oLRZmDHq2zKIKVvwfQ/F+Xx75ckkbqC6WB0mjsinlkqSzWcyqj1qohg2DXzXCzz/wNulj1G0U29wiKiiVOef+SZQLfhUbah/F6lDqiMxrd0KpwHCkgxVcltC82hRHSi8Iam7GboW1KVMLVPS1jNgKe6hh3DGl9+xaGoizPldUMhK9tyYQEFGX72UqJBdDzAg680KiYA/hSbJNCOcddC0NwAS+mMVkYcgd3r9HHLWF1oqWyU/sQhFT0/x281nfK8/FirJgr/N5rOueDgAyC5yryOWC9bucbN2Ag55GThL26D2XMCAwEAATANBgkqhkiG9w0BAQsFAAOCAQEADX8i3PxrMATxyDen1TJNBZf+vhH9X19DONS4M/vUvrEJwjXxmwxfEEyZNRjuxvm7dVQqrRKVciTEr2QF5manLXiqMW0iKuxmtEzcwBiKY90jSVsFAuDlP6Hp5wru3oGEKcdShpo7oXrpuxWoGHwUHcbceP10z7tedACa2f5m89WxbjSFzgLkWTgvQPXJLu4ph9p/76NTsIsXULkB5dKG2L7tODuKeE6QZZbcC6no4lYAx1OZuXk/rOkA7PKdbZBeekFx2zjBjQ13R87jgVZ9V0bVsUqDXAHlrLToYNOML+9m1n6M0vymckcR3+IBHLwvIZIimaVd0rWheXFs4Npumw==',
        ],
        [
            'encryption' => false,
            'signing' => true,
            'type' => 'X509Certificate',
            'X509Certificate' => 'MIIC3DCCAcSgAwIBAgIQdFx0YnixxJRFljR+BEWVPDANBgkqhkiG9w0BAQsFADAqMSgwJgYDVQQDEx9BREZTIFNpZ25pbmcgLSBzdHMubWFoaW5kcmEuY29tMB4XDTIxMDQwNjA1NTAyNloXDTIzMDQwNjA1NTAyNlowKjEoMCYGA1UEAxMfQURGUyBTaWduaW5nIC0gc3RzLm1haGluZHJhLmNvbTCCASIwDQYJKoZIhvcNAQEBBQADggEPADCCAQoCggEBAJxAsbTHzvbHOCt4IjYN3RUxuGqEXTIdO2RfaGpQf8N08NyU3xZiq8fhpyY5HoVI7aXzqMubqjSMlIpa0ORJGYV/RHfy4pPzuNLgmV2/nMYZ9wZ9by8m5GUXD80OFUDq7UMWGYVsfoUFCVYpXNqGgN5jTJ6IturIfmeFoCNm9hsE0LiHQWfBXbsBSzw9hE4fOfWQGHxcP9O68Z5LyU+z+Y9zp7Dn62dcS5Q6F4jO5dFCIAuNkSTCBBLSa96P9OISrH3m8A+swjFNpR5Ad26N6bxmcXku4nroEzO9wj13upYgwPi0HDRLu5BZEfRoUmLnjW82ebcUpCgpc/xMNCYdNbMCAwEAATANBgkqhkiG9w0BAQsFAAOCAQEAGjIDAyqiqhkG3sZfBJ/3OTAF2MKHxEiJSB83hvl2wSXaOvdrLspcEWc91QAVXyRHaIysUtg6yskt8XePJnsuAAna3XpjKWsmu+NTux5OoDP/AIdd2O7JZ83l9CfoAAeTxirdbT4m4n8Xk3wZhBeI2zBR/2IktgK6i9TXjPM8x9bF3Y6toMb+03T/CsLbm0t82x7Tsycdvi/ATgNVuhi9S/5UrMJXpiA/IacNEuNpb2bTlvhZwXYuORvtrvwiazzGSlYjd8WV3Zkv+PmIHWS/eHKxSc5Qh1FWTAMjwO+EnQPvA2SUUs8WObPIq+ZgdAh0ULUgDX5Y3uJ5ZMtNG5i8EQ==',
        ],
    ],
];