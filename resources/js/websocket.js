//for Echo
import Echo from 'laravel-echo';
window.io = require('socket.io-client');
protocol = location.protocol;

//接続情報
window.Echo = new Echo({
    broadcaster: 'socket.io',
    host: `${protocol}://localhost:6001`
});
console.log('jsは生きてます');
//購読するチャネルの設定
