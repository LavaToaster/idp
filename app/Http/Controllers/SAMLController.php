<?php

namespace App\Http\Controllers;

use App\SAML2\Bridge\BuildContainer;
use LightSaml\Builder\Profile\Metadata\MetadataProfileBuilder;
use LightSaml\Idp\Builder\Action\Profile\SingleSignOn\Idp\SsoIdpAssertionActionBuilder;
use LightSaml\Idp\Builder\Profile\WebBrowserSso\Idp\SsoIdpReceiveAuthnRequestProfileBuilder;
use LightSaml\Idp\Builder\Profile\WebBrowserSso\Idp\SsoIdpSendResponseProfileBuilder;
use LightSaml\Logout\Builder\Profile\WebBrowserSlo\SloRequestProfileBuilder;

class SAMLController extends Controller
{
    /**
     * @var BuildContainer
     */
    private $buildContainer;

    public function __construct(BuildContainer $buildContainer)
    {
        $this->buildContainer = $buildContainer;
    }

    public function metadata()
    {
        $builder = new MetadataProfileBuilder($this->buildContainer);

        $context = $builder->buildContext();
        $action = $builder->buildAction();

        $action->execute($context);

        return $context->getHttpResponseContext()->getResponse();
    }

    public function sso()
    {
        $builder = new SsoIdpReceiveAuthnRequestProfileBuilder($this->buildContainer);

        $context = $builder->buildContext();
        $action = $builder->buildAction();

        $action->execute($context);

        $partyContext = $context->getPartyEntityContext();
        $endpoint = $context->getEndpoint();
        $message = $context->getInboundMessage();

        $sendBuilder = new SsoIdpSendResponseProfileBuilder(
            $this->buildContainer,
            array(new SsoIdpAssertionActionBuilder($this->buildContainer)),
            $partyContext->getEntityDescriptor()->getEntityID()
        );
        $sendBuilder->setPartyEntityDescriptor($partyContext->getEntityDescriptor());
        $sendBuilder->setPartyTrustOptions($partyContext->getTrustOptions());
        $sendBuilder->setEndpoint($endpoint);
        $sendBuilder->setMessage($message);

        $context = $sendBuilder->buildContext();
        $action = $sendBuilder->buildAction();

        $action->execute($context);

        return $context->getHttpResponseContext()->getResponse();
    }

    public function slo()
    {
//        $builder = new SloRequestProfileBuilder($this->buildContainer);
//
//        $context = $builder->buildContext();
//        $action = $builder->buildAction();
//
//        $action->execute($context);
//
//        return $context->getHttpResponseContext()->getResponse();
    }
}