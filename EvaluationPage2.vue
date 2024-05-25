<template>
  <ion-page>
    <ion-header>
      <ion-toolbar color="secondary">
        <ion-title>Valoraciones</ion-title>
      </ion-toolbar>
    </ion-header>
    <ion-content>
      <!-- Lista de valoraciones recibidas -->
      <ion-list>
        <ion-list-header style="font-size: 20px;">Valoraciones recibidas</ion-list-header>
        <ion-item v-for="review in receivedReviews" :key="review.id">
          <ion-label>
            <h2>{{ review.usuario }}</h2>
            <div>
              <ion-icon v-for="star in getStarIcons(review.calificacion)" :key="star" :icon="star"
                style="color: orange;"></ion-icon>
              <p>{{ review.comentario }}</p>
            </div>
          </ion-label>
        </ion-item>
      </ion-list>

      <!-- Formulario -->
      <ion-card>
        <ion-card-header>
          <ion-card-title>Nueva valoración</ion-card-title>
        </ion-card-header>
        <ion-card-content>
          <ion-item>
            <ion-label style="font-size: 22px;" position="stacked">Calificación (1-5 estrellas)</ion-label>
            <ion-input v-model="newReview.calificacion" type="number" min="1" max="5"></ion-input>
          </ion-item>
          <ion-item>
            <ion-label style="font-size: 22px;" position="stacked">Comentario</ion-label>
            <ion-textarea v-model="newReview.comentario"></ion-textarea>
          </ion-item>
          <ion-item>
            <ion-label style="font-size: 22px;" position="stacked">Destinatario</ion-label>
            <ion-input v-model="newReview.destinatario"></ion-input>
          </ion-item>
          <ion-button expand="block" @click="submitReview()">Enviar valoración</ion-button>
        </ion-card-content>
      </ion-card>
      <div v-if="errorMessage" class="error-message">{{ errorMessage }}</div>
      <div v-if="showSuccessMessage" class="success-message">
        ¡Valoración enviada con éxito!
      </div>
    </ion-content>
  </ion-page>
</template>

<script lang="ts">
import { defineComponent } from 'vue';
import axios from 'axios';
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
} from '@ionic/vue';
import { starOutline, star } from 'ionicons/icons';

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
  name: 'Valoraciones',
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
  },
  data() {
    return {
      receivedReviews: [] as Review[],
      newReview: {
        calificacion: 0,
        comentario: '',
        destinatario: '',
      },
      userResponse: null as ServerResponse | null,
      errorMessage: '',
      showSuccessMessage: false,
    };
  },
  created() {
    this.generateRandomReviews();
  },
  methods: {
    async generateRandomReviews() {
      const users = [
        'Juan Pérez',
        'María Gómez',
        'Ana González',
        'Carlos Sanz',
        'Laura Escanes',
        'Pedro Martínez',
      ];
      const ratings = [1, 2, 3, 4, 5];
      const comments = [
        '¡Vaya, eso no fue lo que esperaba!',
        'Bueno, podría ser peor...',
        'No está mal, pero no me impresionó',
        '¡Muy bien! ¡Me gustó!',
        '¡Increíble! Lo mejor que he experimentado hasta ahora',
      ];
      const numReviews = 3;
      const randomReviews: Review[] = [];

      for (let i = 0; i < numReviews; i++) {
        const randomUser = users[Math.floor(Math.random() * users.length)];
        const randomRating = ratings[Math.floor(Math.random() * ratings.length)];
        const randomComment = comments[randomRating - 1].replace(
          '[Nombre del usuario]',
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
    async submitReview() {
      try {
        const response = await axios.get<ServerResponse>(
          'http://localhost/proyecto_final/get_user_eval.php'
        );
        this.userResponse = response.data;
        const usuario = this.userResponse.usuario;

        const reviewResponse = await axios.post(
          'http://localhost/proyecto_final/registro_valoracion.php',
          {
            usuario: usuario,
            calificacion: this.newReview.calificacion,
            comentario: this.newReview.comentario,
            destinatario: this.newReview.destinatario,
          }
        );

        if (reviewResponse.status === 200) {
          this.showSuccessMessage = true;
          this.errorMessage = '';
          this.newReview.calificacion = 0;
          this.newReview.comentario = '';
        } else {
          this.errorMessage =
            'Hubo un problema al enviar la valoración. Por favor, inténtelo de nuevo más tarde.';
        }
      } catch (error) {
        this.errorMessage =
          'Error al enviar la valoración. Por favor, verifique su conexión a Internet e inténtelo de nuevo más tarde.';
      }
    },
    getStarIcons(rating: number) {
      const stars = [];
      for (let i = 1; i <= 5; i++) {
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
}

.success-message {
  color: green;
  margin-top: 10px;
  text-align: center;
}
</style>