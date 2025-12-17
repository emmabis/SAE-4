import { MongoClient } from 'mongodb';
import express from 'express';
import swaggerUi from 'swagger-ui-express';
import swaggerJSDoc from 'swagger-jsdoc';
import cors from 'cors';

import helloRoutes from './routes/helloworld.js';
import addMsgRoutes from './routes/addMsg.js';
import deleteMsgRoutes from './routes/deleteMsg.js';
import getMsgRoutes from './routes/getMsg.js';
import getRoomMsgRoutes from './routes/getRoomMsg.js';

const app = express();
app.use(express.json());
app.use(cors());

MongoClient.connect("mongodb://root:mdp@localhost:27017/chatDB?authSource=admin")
  .then(client => {
    console.log('Connecté à MongoDB');
    const db = client.db("chatDB");
    app.locals.db = db;
  })
    .catch(err => console.error('Erreur de connexion MongoDB:', err));

const swaggerOptions = {
  definition: {
    openapi: '3.0.0',
    info: {
      title: 'API Documentation',
      version: '1.0.0',
      description: 'Documentation de l\'API',
    },
  },
  apis: ['./routes/*.js'],
};

const swaggerSpec = swaggerJSDoc(swaggerOptions);

app.use('/api-docs', swaggerUi.serve, swaggerUi.setup(swaggerSpec));

app.use('/api/hello', helloRoutes);
app.use('/api/addMsg', addMsgRoutes);
app.use('/api/deleteMsg', deleteMsgRoutes);
app.use('/api/getMsg', getMsgRoutes);
app.use('/api/getRoomMsg', getRoomMsgRoutes);

app.get('/swagger.json', (req, res) => {
  res.setHeader('Content-Type', 'application/json');
  res.send(swaggerSpec);
});

const port = 5000;
app.listen(port, () => {
  console.log(`Server running at http://localhost:${port}`);
});
