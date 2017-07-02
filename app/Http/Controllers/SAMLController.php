<?php

namespace App\Http\Controllers;

use App\SAML2\Bridge\Container\BuildContainer;
use Illuminate\Contracts\Session\Session;
use Illuminate\Http\Request;
use LightSaml\Builder\Profile\Metadata\MetadataProfileBuilder;
use LightSaml\Idp\Builder\Action\Profile\SingleSignOn\Idp\SsoIdpAssertionActionBuilder;
use LightSaml\Idp\Builder\Profile\WebBrowserSso\Idp\SsoIdpReceiveAuthnRequestProfileBuilder;
use LightSaml\Idp\Builder\Profile\WebBrowserSso\Idp\SsoIdpSendResponseProfileBuilder;

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

    public function init(Request $request)
    {
        $spEntityId = $request->query('sp');

        $spEntityDescriptor = $this->buildContainer->getPartyContainer()->getSpEntityDescriptorStore()->get($spEntityId);

        $criteriaSet = new \LightSaml\Criteria\CriteriaSet([
            new \LightSaml\Resolver\Endpoint\Criteria\BindingCriteria([\LightSaml\SamlConstants::BINDING_SAML2_HTTP_POST]),
            new \LightSaml\Resolver\Endpoint\Criteria\DescriptorTypeCriteria(\LightSaml\Model\Metadata\SpSsoDescriptor::class),
            new \LightSaml\Resolver\Endpoint\Criteria\ServiceTypeCriteria(\LightSaml\Model\Metadata\AssertionConsumerService::class)
        ]);

        $arrEndpoints = $this->buildContainer->getServiceContainer()->getEndpointResolver()->resolve($criteriaSet, $spEntityDescriptor->getAllEndpoints());

        if (empty($arrEndpoints)) {
            throw new \RuntimeException(sprintf('SP party "%s" does not have any SP ACS endpoint defined', $spEntityId));
        }

        $endpoint = $arrEndpoints[0]->getEndpoint();
        $trustOptions = $this->buildContainer->getPartyContainer()->getTrustOptionsStore()->get($spEntityId);

        $sendBuilder = new SsoIdpSendResponseProfileBuilder(
            $this->buildContainer,
            array(new SsoIdpAssertionActionBuilder($this->buildContainer)),
            $spEntityId
        );
        $sendBuilder->setPartyEntityDescriptor($spEntityDescriptor);
        $sendBuilder->setPartyTrustOptions($trustOptions);
        $sendBuilder->setEndpoint($endpoint);

        $context = $sendBuilder->buildContext();
        $action = $sendBuilder->buildAction();

        $action->execute($context);

        return $context->getHttpResponseContext()->getResponse();
    }

    public function sso(Session $session, Request $request)
    {
        if ($request->isMethod('get') && $session->has('saml')) {
            /** @var array $previousSamlRequest */
            $previousSamlRequest = $session->get('saml');
            $session->forget('saml-request');

            $request->setMethod($previousSamlRequest['method']);
            foreach ($previousSamlRequest['request'] as $key => $value) {
                $request->request->set($key, $value);
            }
        }

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