//for Echo
import Echo from 'laravel-echo';
window.io = require('socket.io-client');

let protocol = window.location.protocol;
let hostname = window.location.hostname;
//接続情報
window.Echo = new Echo({
    broadcaster: 'socket.io',
    protocol: protocol,
    host: hostname
});
console.log('jsは生きてます');
//購読するチャネルの設定
