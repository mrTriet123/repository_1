var server = require('http').Server();
var io = require('socket.io')(server);

var Redis = require('ioredis');
var redis = new Redis();

redis.subscribe('order-list-channel');
redis.subscribe('booking-list-channel');
redis.subscribe('walkin-list-channel');
redis.subscribe('notification-channel');

redis.on('message',function(channel,message){
    console.log('Message Received');
    console.log(message);

   var message = JSON.parse(message);
    //console.log('Receive message %s from channel %s', message, channel);

    // emit to all clients

    io.emit( channel + ':' + message.event, message.data);

});

server.listen(3030);
