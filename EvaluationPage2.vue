<template>
  <ion-page>
    <ion-header>
      <ion-toolbar color="secondary">
        <ion-buttons style="margin-left: 8px">
          <!-- Botón de retroceso -->
          <ion-back-button defaultHref="/match"></ion-back-button>
          <ion-title>Valoraciones</ion-title>
        </ion-buttons>
      </ion-toolbar>
    </ion-header>
    <ion-content>
      <ion-list lines="none" class="centered-list">
        <!-- Lista de valoraciones recibidas -->
        <ion-list-header class="list-header"
          >Valoraciones recibidas</ion-list-header
        >
        <ion-item v-for="review in receivedReviews" :key="review.id">
          <ion-label class="ion-margin-start">
            <h2>{{ review.usuario }}</h2>
            <div>
              <!-- Íconos de estrellas -->
              <ion-icon
                v-for="star in getStarIcons(review.calificacion)"
                :key="star"
                :icon="star"
                style="color: orange"
              ></ion-icon>
              <p>{{ review.comentario }}</p>
            </div>
          </ion-label>
        </ion-item>
      </ion-list>
      <!-- Mensajes de error y éxito -->
      <div v-if="errorMessage" class="error-message">{{ errorMessage }}</div>
      <div v-if="showSuccessMessage" class="success-message">
        ¡Valoración enviada correctamente!
      </div>
      <ion-card>
        <ion-card-header>
          <!-- Formulario para enviar una valoración -->
          <ion-card-title class="header">Enviar una valoración</ion-card-title>
        </ion-card-header>
        <ion-card-content>
          <ion-item>
            <ion-label class="form-label" position="stacked"
              >Calificación (1-5 estrellas):</ion-label
            >
            <ion-input
              v-model="newReview.calificacion"
              type="number"
              min="1"
              max="5"
            ></ion-input>
          </ion-item>
          <ion-item>
            <ion-label class="form-label" position="stacked"
              >Comentario:</ion-label
            >
            <ion-textarea v-model="newReview.comentario"></ion-textarea>
          </ion-item>
          <ion-item>
            <ion-label class="form-label" position="stacked"
              >Destinatario:</ion-label
            >
            <ion-input v-model="newReview.destinatario"></ion-input>
          </ion-item>
          <ion-button expand="block" @click="submitReview()">Enviar</ion-button>
        </ion-card-content>
      </ion-card>
    </ion-content>
  </ion-page>
</template>

<script lang="ts">
import { defineComponent } from "vue";
import axios from "axios";
import {
  IonButton,
  IonCard,
  IonCardContent,
  IonCardHeader,
  IonContent,
  IonHeader,
  IonList,
  IonItem,
  IonInput,
  IonIcon,
  IonLabel,
  IonListHeader,
  IonTextarea,
  IonToolbar,
  IonPage,
  IonButtons,
  IonBackButton,
} from "@ionic/vue";
import { starOutline, star } from "ionicons/icons";
import { Capacitor } from "@capacitor/core";
import { env } from "@/env";

interface Review {
  id: number;
  usuario: string;
  calificacion: number;
  comentario: string;
}

interface ServerResponse {
  usuario: string;
}

export default defineComponent({
  name: "Valoraciones",
  components: {
    IonIcon,
    IonCard,
    IonPage,
    IonHeader,
    IonToolbar,
    IonContent,
    IonList,
    IonItem,
    IonLabel,
    IonListHeader,
    IonInput,
    IonCardContent,
    IonCardHeader,
    IonButton,
    IonTextarea,
    IonButtons,
    IonBackButton,
  },
  data() {
    // Datos del componente
    return {
      receivedReviews: [] as Review[],
      newReview: {
        calificacion: 0,
        comentario: "",
        destinatario: "",
      },
      userResponse: null as ServerResponse | null,
      errorMessage: "",
      showSuccessMessage: false,
      serverURL: "",
    };
  },
  created() {
    // Configurar la URL del servidor basada en el entorno
    const isAndroid = Capacitor.isNativePlatform();
    this.serverURL = isAndroid ? env.VITE_ANDROID_SERVER_URL : env.VITE_WEB_SERVER_URL;
    this.serverURL = this.serverURL.replace(/:\d+$/, '');
    // Al cargar el componente
    this.generateRandomReviews();
  },
  methods: {
    // Generar valoraciones aleatorias, sin bloquear hilo principal
    async generateRandomReviews() {
  const users = [
    "Juan Pérez",
    "María Gómez",
    "Ana González",
    "Carlos Sanz",
    "Laura Escanes",
    "Pedro Martínez",
  ];
  const ratings = [1, 2, 3, 4, 5];
  const comments = [
    "¡Vaya, eso no fue lo que esperaba!",
    "Bueno, podría ser peor...",
    "No está mal, pero no me impresionó",
    "¡Muy bien! ¡Me gustó!",
    "¡Increíble! Lo mejor que he experimentado hasta ahora",
  ];
  const numReviews = 3;
  const randomReviews: Review[] = [];
  const usedUserIndexes = new Set(); // Para registrar los usuarios ya utilizados

  for (let i = 0; i < numReviews; i++) {
    let randomUserIndex;
    // Escoge un usuario que aún no haya sido usado
    do {
      randomUserIndex = Math.floor(Math.random() * users.length);
    } while (usedUserIndexes.has(randomUserIndex));
    
    usedUserIndexes.add(randomUserIndex); // Registra el usuario
    
    const randomUser = users[randomUserIndex];
    const randomRating = ratings[Math.floor(Math.random() * ratings.length)];
    const randomComment = comments[randomRating - 1].replace(
      "[Nombre del usuario]",
      randomUser
    );

    const review: Review = {
      id: i + 1,
      usuario: randomUser,
      calificacion: randomRating,
      comentario: randomComment,
    };

    randomReviews.push(review);
  }

  this.receivedReviews = randomReviews;
},
    // Enviar una valoración, sin bloquear hilo principal
    async submitReview() {
      try {
        const response = await axios.get<ServerResponse>(
          `${this.serverURL}/proyecto_final/get_user_eval.php`
        );
        this.userResponse = response.data;
        const usuario = this.userResponse.usuario;

        const reviewResponse = await axios.post(
          `${this.serverURL}/proyecto_final/registro_valoracion.php`,
          {
            usuario: usuario,
            calificacion: this.newReview.calificacion,
            comentario: this.newReview.comentario,
            destinatario: this.newReview.destinatario,
          }
        );

        if (reviewResponse.status === 200) {
          this.showSuccessMessage = true;
          this.errorMessage = "";
          this.newReview.calificacion = 0;
          this.newReview.comentario = "";
          this.newReview.destinatario = "";
        } else {
          this.errorMessage =
            "Hubo un problema al enviar la valoración. Inténtelo de nuevo más tarde.";
          this.showSuccessMessage = false;
        }
      } catch (error) {
        this.errorMessage =
          "Error al enviar la valoración. Verifique su conexión e inténtelo de nuevo.";
        this.showSuccessMessage = false;
      }
      setTimeout(() => {
        this.showSuccessMessage = false;
      }, 3000);
    },
    // Obtener estrellas según calificación
    getStarIcons(rating: number) {
      const stars = []; // Array para almacenar iconos
      for (let i = 1; i <= 5; i++) { // Itera de 1 a 5
        if (i <= rating) {
          stars.push(star);
        } else {
          stars.push(starOutline);
        }
      }
      return stars;
    },
  },
});
</script>

<style scoped>
.error-message {
  color: red;
  margin-top: 10px;
  text-align: center;
}

.success-message {
  color: green;
  margin-top: 10px;
  text-align: center;
}

.form-label {
  font-weight: bold;
  font-size: 1.3rem;
}

.header {
  color: rgb(150, 83, 238);
}

.list-header {
  margin-top: 20px;
  display: inline-block;
  font-size: 20px;
  text-align: center;
  width: auto;
  margin: 20px auto;
  color: rgb(98, 26, 190);
}
.centered-list {
  display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    width: 100%;
    max-width: 100%;
}
.centered-list ion-item {
    width: 100%;
    max-width: 400px;
    margin: 0 auto;
}
</style>
