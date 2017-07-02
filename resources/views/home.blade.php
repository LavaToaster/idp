@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Dashboard</div>

                <div class="panel-body">

                    <ui class="nav nav-pills nav-stacked">
                    @foreach($serviceProviderEntities as $serviceProviderEntity)
                        <?php /** @var \LightSaml\Model\Metadata\EntityDescriptor $serviceProviderEntity */ ?>

                        <li>
                            <a href="{{ route('saml.idp.init', ['sp' => $serviceProviderEntity->getEntityID()]) }}">
                                {{ $serviceProviderEntity->getEntityID() }}
                            </a>
                        </li>
                    @endforeach
                    </ui>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
