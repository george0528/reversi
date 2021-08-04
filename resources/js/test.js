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
window.Echo.join(`test.1`)
    .here((u) => {
        console.log('join出来ました');
        console.log(u);
    })
    .joining((user) => {
        console.log('誰かが参加しました');
        console.log(user);
    })
    .leaving((user) => {
        console.log('誰かが抜けました');
        console.log(user)
    })
    .error((error) => {
        console.error(error);
    })
    .listen('PresenceEvent',(e) => {
        console.log('listen出来ました');
        console.log(e);
    });