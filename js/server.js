const express = require('express');
const app = express();
const server = require('http').createServer(app);
const io = require('socket.io')(server, {
  cors: {
    origin: "*", 
  }
});

app.use(express.json());
app.use(express.urlencoded({ extended: true }));


app.post('/notify', (req, res) => {
  const data = req.body;
  io.emit('notify', data);
  res.status(200).send('Notification sent to all clients');
});

io.on('connection', (socket) => {
  console.log('Client connected...');
  socket.on('disconnect', () => {
    console.log('Client disconnected');
  });
});

server.listen(3000, () => {
  console.log('WebSocket Server listening on port 3000');
});
