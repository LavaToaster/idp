<?php

namespace App\Http\Controllers;

use App\SAML2\Bridge\PartyContainer;
use Illuminate\Http\Request;

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
        $serviceProviderEntities = $partyContainer->getSpEntityDescriptorStore()->all();

        return view('home', [
            'serviceProviderEntities' => $serviceProviderEntities
        ]);
    }
}
