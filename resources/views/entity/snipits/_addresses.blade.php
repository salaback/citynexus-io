<div class="row">
    <div class="col-sm-6">
        <div class="boxs">
            <div class="boxs-header">
                <h1 class="custom-font">Mailing Addresses</h1>
            </div>
            <div class="boxs-body">
                <div class="list-group">
                    @if($entity->mailingAddress != null)
                        <div class="list-group-item">
                            <b>Primary Address</b>
                            {{$address->mailingAddress->address}}<br>
                            {{$address->mailingAddress->city}}, {{$address->mailingAddress->state}} {{$address->mailingAddress->postcode}}
                        </div>
                    @endif
                    @foreach($entity->addresses as $address)
                        @if($address->id != $entity->mailing)
                            <div class="list-group-item">
                                <a href="">Make Address Primary</a> <br>

                                {{$address->address}}<br>
                                {{$address->city}}, {{$address->state}} {{$address->postcode}}
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>