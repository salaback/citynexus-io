@extends('master.main')

@section('title', 'Create Client')

@section('main')
<form action="{{route('client.store')}}" id="createGroup" onsubmit="return validateForm()" method="POST" class="form-horizontal" role="form">
    {{csrf_field()}}

    <section class="boxs ">
        <div class="boxs-header dvd dvd-btm">
            <h1 class="custom-font"><strong>Create</strong> Client</h1>
        </div>
        <div class="boxs-body">
            <div class="panel-body">
                <h4>Client Information</h4>
                <div class="form-group">
                    <label for="name" class="col-sm-2 control-label">Client Name</label>
                    <div class="col-sm-10">
                        <input type="text" name="client[name]" id="name" class="form-control" value="" title="" required="required" >
                    </div>
                </div>
                <div class="form-group">
                    <label for="domain" class="col-sm-2 control-label">Domain</label>
                    <div class="col-sm-10">
                        <div class="input-group">
                            <div class="input-group-addon">https://</div>
                            <input type="text" class="form-control" name="client[domain]" id="domain" placeholder="sub-domain">
                            <div class="input-group-addon">.citynexus.io</div>
                        </div>
                        <p class="help-block mb-0">Choose a sub domain with lowercase letters, numbers, and hyphens</p>

                    </div>
                </div>
            </div>

            <div class="panel-body">
                <h4>Primary Account Owner</h4>
                <div class="form-group">
                    <label for="first_name" class="col-sm-2 control-label">First Name</label>
                    <div class="col-sm-10">
                        <input type="text" name="user[first_name]" id="first_name" class="form-control" value="" title="" required="required" >
                    </div>
                </div>
                <div class="form-group">
                    <label for="lName" class="col-sm-2 control-label">Last Name</label>
                    <div class="col-sm-10">
                        <input type="text" name="user[last_name]" id="last_name" class="form-control" value="" title="" required="required" >
                    </div>
                </div>
                <div class="form-group">
                    <label for="email" class="col-sm-2 control-label">Email</label>
                    <div class="col-sm-10">
                        <input type="email" name="user[email]" id="email" class="form-control" value="" title="" required="required" >
                    </div>
                </div>

            </div>
        </div>
        <div class="boxs-footer">
            <button type="submit" class="btn btn-raised btn-primary">Create Client</button>
        </div>
    </section>
</form>

@endsection

@push('scripts')
<script>
    function validUrl(string) {
        return /[^a-zA-Z0-9-]/.test( string );
    }

    function validateForm() {
        var url = document.forms["createGroup"]["domain"].value;
        if (validUrl(url)) {
            alert("warning", "That sub domain doesn't appear to be valid.");
            return false;
        }
        if (url == null) {
            alert("warning", "Please create a sub domain");
            return false;
        }
        if(document.forms["createGroup"]["name"].value == null) {
            alert("warning", "Please name the group.");
            return false;
        }

    }



</script>
@endpush