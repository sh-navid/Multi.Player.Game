"use strict";

const express = require("express");
const app = express();
const server = require("http").createServer(app);
const io = require("socket.io")(server, {
    cors: { origin: `*`, methods: ["GET", "POST"] },
});

let playerCounter = -1;
let players = [
  null,
  null,
  null,
  null,
  null,
  null,
  null,
  null,
  null,
  null,
  null,
  null,
];

io.on("connection", (socket) => {
  console.log("a user connected");
  socket.on("disconnect", () => {
    console.log("user disconnected");
  });

  socket.on("player position all", () => {
    socket.emit("player position all", players);
  });

  socket.on("player move", (msg) => {
    players[msg.idx] = msg;
    io.emit("player move", msg);
    console.log(msg);
  });

  socket.on("player assign", () => {
    playerCounter++;
    if (playerCounter <= 11) {
      io.emit("player assign", playerCounter);
    }
  });
});

server.listen(3000, () => {
  console.log("listening on *:3000");
});
