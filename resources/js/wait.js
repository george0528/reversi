//for Echo
import Echo from 'laravel-echo';
window.io = require('socket.io-client');

//接続情報
window.Echo = new Echo({
    broadcaster: 'socket.io',
    host: 'http://localhost:6001',
});
const params = new URLSearchParams(location.search)
let room_id = params.get('room_id');
window.Echo.channel('laravel_database_private-match.'+room_id)
    .listen('RoomEvent', (e) => {
        console.log(e);
        setTimeout(() => {
            location.href = 'http://localhost:8000/mode/online/room/battle';
        }, 2000);
    });