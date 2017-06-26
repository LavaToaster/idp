<?php

namespace App\Http\Controllers;

use Illuminate\Filesystem\FilesystemManager;
use Illuminate\Http\Request;
use SAML2\Binding;
use SAML2\Certificate\X509;
use SAML2\Constants;
use SAML2\XML\ds\KeyInfo;
use SAML2\XML\ds\X509Certificate;
use SAML2\XML\ds\X509Data;
use SAML2\XML\md\ContactPerson;
use SAML2\XML\md\EndpointType;
use SAML2\XML\md\EntityDescriptor;
use SAML2\XML\md\IDPSSODescriptor;
use SAML2\XML\md\KeyDescriptor;

class SAMLController extends Controller
{
    public function metadata(FilesystemManager $filesystem)
    {
        $entityDescriptor = new EntityDescriptor();
        $entityDescriptor->entityID = \route('saml.idp.metadata');

        $IDPSSODescriptor = new IDPSSODescriptor();
        $IDPSSODescriptor->protocolSupportEnumeration = [Constants::NS_SAMLP];

        $samlCert = $this->parseCert($filesystem->drive()->get('keys/saml.crt'));

        $IDPSSODescriptor->KeyDescriptor = [
            $this->getKeyDescriptor($samlCert, 'signing'),
            $this->getKeyDescriptor($samlCert, 'encryption')
        ];
        $IDPSSODescriptor->NameIDFormat = [Constants::NAMEID_TRANSIENT];

        $ssoEndpoint = new EndpointType();
        $ssoEndpoint->Binding = Constants::BINDING_HTTP_REDIRECT;
        $ssoEndpoint->Location = route('saml.idp.sso');

        $sloEndpoint = new EndpointType();
        $sloEndpoint->Binding = Constants::BINDING_HTTP_REDIRECT;
        $sloEndpoint->Location = route('saml.idp.slo');

        $IDPSSODescriptor->SingleSignOnService = [$ssoEndpoint];
        $IDPSSODescriptor->SingleLogoutService = [$sloEndpoint];

        $entityDescriptor->RoleDescriptor[] = $IDPSSODescriptor;

        $contactPerson = new ContactPerson();
        $contactPerson->contactType = 'technical';
        $contactPerson->GivenName = \config('saml.contact.name');
        $contactPerson->EmailAddress = [\config('saml.contact.email')];

        $entityDescriptor->ContactPerson = [$contactPerson];

        $dom = $entityDescriptor->toXML();

        $xml = \simplexml_import_dom($dom)->asXML();

        return response()->make($xml, 200, ['Content-Type' => 'text/xml']);
    }

    public function sso(Request $request)
    {
        dd(Binding::getCurrentBinding());
    }

    public function slo()
    {
        //
    }

    /**
     * @param string $samlCert
     * @param string $use
     * @return KeyDescriptor
     */
    private function getKeyDescriptor(string $samlCert, string $use): KeyDescriptor
    {
        $x509Certificate = new X509Certificate();
        $x509Certificate->certificate = $samlCert;

        $x509Data = new X509Data();
        $x509Data->data = [$x509Certificate];

        $keyInfo = new KeyInfo();
        $keyInfo->info = [$x509Data];

        $keyDescriptor = new KeyDescriptor();
        $keyDescriptor->use = $use;
        $keyDescriptor->KeyInfo = $keyInfo;

        return $keyDescriptor;
    }

    /**
     * @param $cert
     * @return mixed
     */
    private function parseCert($cert): string
    {
        $values = [
            "\n",
            '-----BEGIN CERTIFICATE-----',
            '-----END CERTIFICATE-----',
        ];

        foreach ($values as $value) {
            $cert = \str_replace($value, '', $cert);
        }

        return $cert;
    }
}