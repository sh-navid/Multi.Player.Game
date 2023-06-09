<!DOCTYPE html>
<html>

<head>
    <title>Multiplayer Tank Game</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <script src="http://localhost:3000/socket.io/socket.io.js"></script>
    <script>
        let socket = io.connect(":3000");
    </script>
    <script src="https://pixijs.download/release/pixi.js"></script>
</head>

<body style="overflow-y: hidden">
    <a href="/logout">Logout</a>
    <div id="game-holder" style="text-align: center"></div>
    <script>
        let speed = 15;
        let currentPlayerIndex = {{ Auth::id() }};
        let players = [];
        let name = "{{ Auth::user()->name }}"

        window.onload = () => {
            socket.emit("player position all");
        };

        let setPos = (msg) => {
            players[msg.idx].x = msg.x;
            players[msg.idx].y = msg.y;
            players[msg.idx].newRotation = msg.newRotation;
        };

        socket.on("player move", function(msg) {
            if (msg.idx === currentPlayerIndex) return;
            setPos(msg);
        });

        socket.on("player position all", function(msg) {
            msg.forEach((data) => {
                if (data !== null)
                    if (data.idx !== currentPlayerIndex) setPos(data);
            });
        });

        document.onkeydown = function(event) {
            switch (event.keyCode) {
                case 37:
                    players[currentPlayerIndex].x -= speed;
                    players[currentPlayerIndex].newRotation = (-90 * Math.PI) / 180;
                    break;
                case 38:
                    players[currentPlayerIndex].y -= speed;
                    players[currentPlayerIndex].newRotation = 0;
                    break;
                case 39:
                    players[currentPlayerIndex].x += speed;
                    players[currentPlayerIndex].newRotation = (+90 * Math.PI) / 180;
                    break;
                case 40:
                    players[currentPlayerIndex].y += speed;
                    players[currentPlayerIndex].newRotation = (180 * Math.PI) / 180;
                    break;
            }
            socket.emit("player move", {
                idx: currentPlayerIndex,
                x: players[currentPlayerIndex].x,
                y: players[currentPlayerIndex].y,
                newRotation: players[currentPlayerIndex].newRotation,
                name: name,
            });
        };

        let W = 900,
            H = 550;

        let app = new PIXI.Application({
            width: W,
            height: H
        });
        document.getElementById("game-holder").appendChild(app.view);

        let sp_w = 120;
        let sp_h = 120;

        for (let i = 0; i <= 20; i++) {
            let sprite = PIXI.Sprite.from("/sprites/sprite_tank_" + i + ".png");
            sprite.width = sp_w;
            sprite.height = sp_h;
            sprite.newRotation = 0; //my custom property
            sprite.name = "";

            sprite.pivot.set(400, 400);
            sprite.position.set(W / 2, H / 2);
            app.stage.addChild(sprite);
            players.push(sprite);
        }

        let elapsed = 0.0;
        app.ticker.add((delta) => {
            elapsed += delta;

            players.forEach((sp) => {
                let r = sp.rotation;
                let nr = sp.newRotation;
                let s = 0.2;
                if (r + s < nr) r += s;
                else if (r - s > nr) r -= s;
                else r = nr;
                sp.rotation = r;
            });
        });
    </script>

    @foreach ($users as $user)
        <div style="display: inline-block;text-align: center">
            <img src="/sprites/sprite_tank_{{ $user->id }}.png" style="width: 5em" alt="">
            @if ($user->id === Auth::id())
            <span style="display: block; border:1px solid gold">{{ $user->name }}</span>
            @else
            <span style="display: block; border:1px solid gray">{{ $user->name }}</span>
            @endif
            
        </div>
    @endforeach
</body>

</html>
