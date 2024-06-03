const express = require("express"); // Importar Express
const axios = require("axios"); // Importar Axios para hacer solicitudes HTTP
const cors = require("cors"); // Importar CORS para permitir solicitudes desde el emulador

const app = express(); // Crear una instancia de Express

// Configurar CORS para permitir solicitudes desde localhost:8101 y 10.0.2.2:8101
const corsOptions = {
  origin: ["http://localhost:8101", "http://10.0.2.2:8101"],
  credentials: true,
};
app.use(cors(corsOptions));

// Ruta para hacer la solicitud a la API externa
app.get("/api/usuarios", async (req, res) => {
  try {
    // Hacer la solicitud a la API externa
    const response = await axios.get("https://randomuser.me/api/?results=20&nat=ES");

    // Devolver los datos obtenidos como respuesta
    res.json(response.data);
  } catch (error) {
    // Manejar errores
    console.error("Error al obtener datos de la API externa:", error);
    res.status(500).json({ error: "Error al obtener datos de la API externa" });
  }
});

// Puerto en el cual el servidor Express escucha solicitudes
const PORT = process.env.PORT || 3000;
app.listen(PORT, () => {
  console.log(`Servidor Express escuchando en el puerto ${PORT}`);
});