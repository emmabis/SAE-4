import { Router } from 'express';
const router = Router();

/**
 * @swagger
 * /api/hello:
 *   get:
 *     description: hello world!
 *     responses:
 *       200:
 *         description: Succès
 */
router.get('/', (req, res) => {
    res.send('Hello, World!');
});

export default router;
