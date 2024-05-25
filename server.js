const express = require('express');
const axios = require('axios');
const cors = require('cors');

const app = express();

app.use(cors({
  origin: 'http://localhost:8101',
  credentials: true
}));

app.get('/api/usuarios', async (req, res) => {
  try {
    const response = await axios.get('https://randomuser.me/api/?results=20&nat=ES');

    res.json(response.data);
  } catch (error) {
    console.error('Error al obtener datos de la API externa:', error);
    res.status(500).json({ error: 'Error al obtener datos de la API externa' });
  }
});

const PORT = process.env.PORT || 3000;
app.listen(PORT, () => {
  console.log(`Servidor Express escuchando en el puerto ${PORT}`);
});