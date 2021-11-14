//for Echo
import Echo from 'laravel-echo';
window.io = require('socket.io-client');

let hostname = window.location.hostname;
//接続情報
window.Echo = new Echo({
    broadcaster: 'socket.io',
    // protocol: 'http',
    // hostname: hostname,
    // port: 6001
    host: 'http://localhost:6001'
});
console.log('jsは生きてます');
//購読するチャネルの設定
