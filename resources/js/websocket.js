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