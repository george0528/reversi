import Echo from __dirname+"../../node_modules/laravel-echo";

window.io = require('socket.io-client');

window.Echo = new Echo({
    broadcaster: 'socket.io',
    host: 'http://' + window.location.hostname + ':6001'
});
window.Echo.channel('test')
    .listen('MessageRecieved', (e) => {
        console.log(e);
    });