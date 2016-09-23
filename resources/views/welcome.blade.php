@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Welcome</div>

                <div class="panel-body">
                    Your Application's Landing Page.
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
@section('script');
<script src="https://cdn.socket.io/socket.io-1.4.5.js"></script>
<script>
     // var ns = io.connect('127.0.0.1:8000/test');
     var guest = io.connect('127.0.0.1:8000/guest');

     // guest.on('guest-broadcast', function(data){
     //    console.log('guest broadcast');
     // });

     // ns.emit('guest-namespace',{});
</script>
@endsection