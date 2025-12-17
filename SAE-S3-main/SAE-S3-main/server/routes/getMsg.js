// routes/getMsg.js
import { Router } from 'express';
const router = Router();

/**
 * @swagger
 * /api/getMsg:
 *   get:
 *     description: Get messages
 *     responses:
 *       200:
 *         description: Success
 */

router.get('/', async (req, res) => {
  try {
    const db = req.app.locals.db; 
    const messages = await db.collection('messages').find().toArray();
    res.json(messages);
  } catch (err) {
    res.status(500).send('Erreur du serveur');
  }
});

export default router;
