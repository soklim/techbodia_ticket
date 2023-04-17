@extends('layouts.app')
@section('content')
<div class="row">
    <div class="col-md-2"></div>
    <div class="col-md-8" style="background-color: #ffffff; border-radius: 10px; box-shadow: 0 0 5px #888888; top: 50%">
        <div class="row" style="display: flex;justify-content: center;padding:40px">
            <div class="col-md-6">
                <img src="/assets/images/access_denied.png" style="width:100%"/>
            </div>
            <div class="col-md-6">
                <h1>405 Permission Denied</h1><br /><hr /><br />
                <h3>អ្នកមិនមានសិទ្ធលើការងារនេះទេ !!!!</h3><br />
                <a href="/" type="button" class="btn btn-primary"><i class="bx bx-home"></i> ទៅទំព័រដើម</a>
            </div>
        </div>
    </div>
    <div class="col-md-2"></div>
</div>
@endsection
