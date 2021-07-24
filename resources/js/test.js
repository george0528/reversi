//for Echo
import Echo from 'laravel-echo';
window.io = require('socket.io-client');

//接続情報
window.Echo = new Echo({
    broadcaster: 'socket.io',
    host: 'http://localhost:6001',
});

console.log('jsは生きてます');
//購読するチャネルの設定
// window.Echo.channel('laravel_database_public-event')
window.Echo.channel('laravel_database_test')
    .listen('Test', function(e) {
        console.log('Testが発火されました。');
        console.log(e);
    });
window.Echo.channel('laravel_database_public-event')
    .listen('PublicEvent', function(e) {
        console.log('PublicEventが発火されました。');
        console.log(e);
    });