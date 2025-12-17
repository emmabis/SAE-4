import { Router } from 'express';
const router = Router();

/**
 * @swagger
 * /api/getRoomMsg:
 *   get:
 *     description: Get messages of a room
 *     parameters:
 *       - in: query
 *         name: room
 *         schema:
 *           type: string
 *         required: true
 *         description: The room ID
 *     responses:
 *       200:
 *         description: Success
 */

router.get('/', async (req, res) => {
    const { room } = req.query;

    if (!room) {
        return res.status(400).json({ error: "Le paramètre 'room' est requis" });
    }

    try {
        const db = req.app.locals.db; 
        const messages = await db.collection('messages').find({ room }).toArray();
        res.json(messages);
    } catch (err) {
        console.error("Erreur serveur:", err);
        res.status(500).send('Erreur du serveur');
    }
});

export default router;
