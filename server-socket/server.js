const express = require('express');
const app = express();
const http = require('http');
const server = http.createServer(app);
const { Server } = require("socket.io");
const io = new Server(server);

app.get('/', (req, res) => res.sendFile(__dirname + '/public/index.html'));
app.get('/engine', (req, res) => res.sendFile(__dirname + '/public/engine.all.js'));
app.get('/game-style', (req, res) => res.sendFile(__dirname + '/public/index-game.css'));
app.get('/game-script', (req, res) => res.sendFile(__dirname + '/public/index-game.js'));

for (let i = 0; i <= 11; i++)
    app.get('/sprite-tank-' + i, (req, res) => res.sendFile(__dirname + `/public/sprites/sprite_tank_${i}.png`));

let playerCounter = -1;
let players = [null, null, null, null, null, null, null, null, null, null, null, null];
let chats = [];

io.on('connection', (socket) => {
    console.log('a user connected');
    socket.on('disconnect', () => {
        console.log('user disconnected');
    });

    socket.on('player position all', () => {
        socket.emit('player position all', players);
    });

    socket.on('player move', (msg) => {
        players[msg.idx] = msg;
        io.emit('player move', msg);
        console.log(msg);
    });

    socket.on('player assign', () => {
        playerCounter++;
        if (playerCounter <= 11) {
            io.emit('player assign', playerCounter);
        }
    });
});

server.listen(3000, () => {
    console.log('listening on *:3000');
});