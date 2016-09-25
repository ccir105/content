const SERVER_PORT = 8000

//

var express = require('express')
var app = express()
var server = require('http').Server( app );
var io = require('socket.io').listen( server );
var jwt = require('jsonwebtoken');
var redis = require('redis')
var ioredis = require('socket.io-redis')
var _ = require('underscore');

require('dotenv').config({path: '/var/www/html/content/.env'});

var jwtSecret = process.env.JWT_SECRET;
io.adapter(ioredis({host: 'localhost', port: 6379}))

var sub = redis.createClient()


var guest = io.of('/guest');
var auth = io.of('/auth');

var allNs = {
    'reset.success' : guest
};

var so = {};


guest.on('connection', function(socket)
{
    socket.on('password-reset-channel', function(data){
        
        sub.subscribe( data.channel );

        socket.join( data.channel );

        socket.emit('reset:joined', {'joined' : true});
    });
});

sub.subscribe('advice:update');

auth.on('connection', function(socket)
{
    socket.on('authentication', function(data)
    {
        jwt.verify( data.token, jwtSecret , function(err, decoded){

            if( err ) 
            {
                socket.disconnect('Disconnect');
                
                return;
            }
            
            socket.user_id = decoded.user_id; 
            
            so[decoded.user_id] = socket;
                
            socket.emit('authenticated', decoded);

            socket.join('advice:update');
        });
    });

    socket.on('logout', function(data){
        socket.disconnect();
    });
});

sub.on('message', function (channel, payload)
{   
    payload = JSON.parse(payload)
    
    payload.data._channel = channel;

    var currentNs = allNs[payload.event];
    
    if( currentNs )
    {
        currentNs.in(channel).emit(payload.event, payload.data)
    }
    else
    {
        var creatorSocket = so[payload.data.user_id];

        if(creatorSocket)
        {
            creatorSocket.broadcast.in(channel).emit(payload.event, payload.data);
        }
        else
        {
            auth.in(channel).emit(payload.event, payload.data);
        }
    }
});

server.listen(SERVER_PORT, function () {
    console.log('Listening to incoming client connections on port ' + SERVER_PORT)
});