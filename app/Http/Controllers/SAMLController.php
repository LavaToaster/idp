<?php

namespace App\Http\Controllers;

use App\SAML2\Bridge\BuildContainer;
use Illuminate\Http\Request;
use LightSaml\Binding\BindingFactory;
use LightSaml\Builder\Profile\Metadata\MetadataProfileBuilder;
use LightSaml\Context\Profile\MessageContext;

class SAMLController extends Controller
{
    public function metadata(BuildContainer $buildContainer)
    {
        $builder = new MetadataProfileBuilder($buildContainer);

        $context = $builder->buildContext();
        $action = $builder->buildAction();

        $action->execute($context);

        return $context->getHttpResponseContext()->getResponse();
    }

    public function sso(BindingFactory $bindingFactory, Request $request)
    {
        $binding = $bindingFactory->getBindingByRequest($request);

        $messageContext = new MessageContext();
        $binding->receive($request, $messageContext);

        dd($messageContext->asAuthnRequest());
    }

    public function slo()
    {
        //
    }
}