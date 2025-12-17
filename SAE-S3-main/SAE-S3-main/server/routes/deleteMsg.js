import { Router } from 'express';
import { ObjectId } from 'mongodb';
const router = Router();

/**
 * @swagger
 * /api/deleteMsg:
 *   delete:
 *     description: Delete a message
 *     requestBody:
 *       required: true
 *       content:
 *         application/json:
 *           schema:
 *             type: object
 *             properties:
 *               _id:
 *                 type: string
 *     responses:
 *       200:
 *         description: Task deleted successfully
 *       404:
 *         description: Task not found
 */

router.delete('/', async (req, res) => {
  console.log("Body reçu :", req.body);
  const { _id } = req.body;
  try {
    const db = req.app.locals.db;
    const result = await db.collection('messages').deleteOne({ _id: new ObjectId(_id) });
    if (result.deletedCount === 1) {
        res.status(200).json({ message: 'Message supprimée avec succès' });
        console.log("code : 200");
      } else {
        res.status(404).json({ message: 'Message non trouvée' });
        console.log("code : 404");
      }
  } catch (err) {
    console.error('Erreur lors de la suppression du message :', err);
    res.status(500).json({ message: 'Erreur de la suprresion du message', error: err.message });
  }
});



export default router;
