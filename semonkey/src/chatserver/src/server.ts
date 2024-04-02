import WebSocket from 'ws';

const wss = new WebSocket.Server({ port: 2024 });

wss.on('connection', (ws: WebSocket) => {
    console.log('New client connected');

    ws.on('message', (message: string) => {
        console.log("Received message %s", message);
        ws.send(`${message}`.toUpperCase());
    });

    ws.on('close', () => {
        console.log('Client disconnected');
    });
});