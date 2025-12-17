import { Router } from 'express';
const router = Router();

/**
 * @swagger
 * /api/addMsg:
 *   post:
 *     description: Add a message
 *     requestBody:
 *       required: true
 *       content:
 *         application/json:
 *           schema:
 *             type: object
 *             properties:
 *               room:
 *                 type: string
 *               content:
 *                 type: string
 *               date:
 *                 type: string
 *               author:
 *                 type: string
 *     responses:
 *       201:
 *         description: Task added successfully
 */

router.post('/', async (req, res) => {
  console.log("Body reçu :", req.body);
  const { room, content,date,author } = req.body;
  try {
    const db = req.app.locals.db;
    const message = { room, content,date,author };
    const result = await db.collection('messages').insertOne(message);
    const insertedMsg = await db.collection('messages').findOne({ _id: result.insertedId });
    res.status(201).json(insertedMsg);
    console.log("code : 201");
  } catch (err) {
    console.error('Erreur lors de l\'ajout de la tâche :', err);
    res.status(500).json({ message: 'Erreur d\'ajout de la tâche', error: err.message });
    console.log("code : 500");
  }
});



export default router;
