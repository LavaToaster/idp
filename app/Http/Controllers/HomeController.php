<?php

namespace App\Http\Controllers;

use App\SAML2\Bridge\Container\PartyContainer;
use LightSaml\Model\Metadata\EntityDescriptor;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(PartyContainer $partyContainer)
    {
        $serviceProviderEntities = array_filter($partyContainer->getSpEntityDescriptorStore()->all(), function(EntityDescriptor $entityDescriptor) {
            return count($entityDescriptor->getAllSpSsoDescriptors()) > 0;
        });

        return view('home', [
            'serviceProviderEntities' => $serviceProviderEntities
        ]);
    }
}
