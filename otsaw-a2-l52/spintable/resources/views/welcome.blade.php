<!DOCTYPE html>
<html>
    <head>

        <!-- Metadata -->
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta charset="utf-8">


        <!-- CSS -->



        <!-- JS -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/vue/1.0.25/vue.min.js" language="javascript"></script>
        <script src="https://cdn.socket.io/socket.io-1.4.5.js" language="javascript"></script>
        <title>Quabii</title>


    </head>
    <body>

        <h2>Socket.io</h2>

            <ul>
                <li v-for="user in users" track-by="$index">@{{ user }}</li>

                {{--<li v-repeat="user: users" v-text="user"></li>--}}
            </ul>
        <script>
            var socket = io('http://192.168.10.10:3030');
            new Vue({
                el: 'body',

                data: {
                    users: []
                },

                ready: function(){
                    // socket.on('order-list-channel:App\\Events\\UpdateOrderList',function(data){
                    // socket.on('booking-list-channel:App\\Events\\UpdateBookingList',function(data){
                    // socket.on('walkin-list-channel:App\\Events\\UpdateWalkinList',function(data){
                    socket.on('notification-channel:App\\Events\\RefreshNotifications',function(data){
                        console.log(data);
                    }.bind(this));
                }
            });
        </script>
    </body>
</html>
