<template>
  <ion-page>
    <ion-header :translucent="true">
      <ion-toolbar color="secondary">
        <ion-title size="large">Perfil de usuario</ion-title>
      </ion-toolbar>
    </ion-header>

    <ion-content :fullscreen="true">
      <ion-header collapse="condense">
        <ion-toolbar>
          <ion-title size="large">Perfil de usuario</ion-title>
        </ion-toolbar>
      </ion-header>

      <div class="chip-container">
        <ion-chip @click="irABuscarUsuarios" class="search-chip" color="medium">
          <ion-icon :icon="search"></ion-icon>
          <ion-label>Buscar usuarios</ion-label>
        </ion-chip>
      </div>
      <div class="container">
        <div class="user-info">
          <ion-card>
            <ion-card-header>
              <ion-card-title><strong>{{ userData.nombre }}</strong></ion-card-title>
            </ion-card-header>
            <ion-card-content>
              <p><strong>Edad:</strong> {{ userData.edad }}</p>
              <p><strong>Correo electrónico:</strong> {{ userData.correo }}</p>
              <p><strong>Ubicación:</strong> {{ userData.ubicacion }}</p>
            </ion-card-content>
          </ion-card>
        </div>

        <h3 class="section-title">Mis habilidades</h3>
        <div v-for="(habilidad, index) in habilidades" :key="index" class="habilidad-card">
          <ion-card>
            <ion-card-header>
              <div class="card-header-content">
                <ion-card-title>{{ habilidad.nombreHabilidad }}</ion-card-title>
                <ion-segment value="default" class="eliminar-segment" color="danger">
                  <ion-segment-button value="eliminar" @click="eliminarHabilidad(index)">
                    <ion-label color="danger">Eliminar</ion-label>
                  </ion-segment-button>
                </ion-segment>
              </div>
            </ion-card-header>
            <ion-card-content>
              <p><strong>Descripción:</strong> {{ habilidad.descripcion }}</p>
              <p><strong>Categoría:</strong> {{ habilidad.categoria }}</p>
              <p><strong>Nivel:</strong> {{ habilidad.nivel }}</p>
            </ion-card-content>
          </ion-card>
        </div>

        <ion-card class="agregar">
          <ion-card-header>
            <div v-if="errorMessage" class="message error-message">
        {{ errorMessage }}
      </div>
      <div v-if="successMessage" class="message success-message">
        {{ successMessage }}
      </div>
            <ion-card-title>Agregar nueva habilidad</ion-card-title>
          </ion-card-header>
          <ion-card-content>
            <form @submit.prevent="agregarHabilidad()">
              <ion-item>
                <ion-label position="fixed">Nombre:</ion-label>
                <ion-select placeholder="Seleccione una opción" v-model="nuevaHabilidad.nombreHabilidad" interface="action-sheet" cancel-text="Cancelar">
                  <ion-select-option v-for="habilidad in habilidadesPredefinidas" :key="habilidad" :value="habilidad">{{ habilidad }}</ion-select-option>
                </ion-select>
              </ion-item>

              <ion-item>
                <ion-label position="fixed">Descripción:</ion-label>
                <ion-input placeholder="Breve descripción..." v-model="nuevaHabilidad.descripcion"></ion-input>
              </ion-item>

              <ion-item>
                <ion-label position="fixed">Categoría:</ion-label>
                <ion-select placeholder="Seleccione una opción" v-model="nuevaHabilidad.categoria" interface="alert" cancel-text="Cancelar">
                  <ion-select-option v-for="categoria in categorias" :key="categoria" :value="categoria">{{ categoria }}</ion-select-option>
                </ion-select>
              </ion-item>

              <ion-item>
                <ion-label position="fixed">Nivel:</ion-label>
                <ion-select placeholder="Seleccione una opción" v-model="nuevaHabilidad.nivel" interface="popover">
                  <ion-select-option value="principiante">Principiante</ion-select-option>
                  <ion-select-option value="intermedio">Intermedio</ion-select-option>
                  <ion-select-option value="avanzado">Avanzado</ion-select-option>
                </ion-select>
              </ion-item>
              <ion-button expand="block" type="submit" color="primary">Agregar</ion-button>
            </form>
          </ion-card-content>
        </ion-card>
      </div>
    </ion-content>
  </ion-page>
</template>

<script lang="ts">
import { defineComponent } from "vue";
import { IonPage, IonHeader, IonToolbar, IonTitle, IonContent, IonCard, IonCardHeader, IonCardTitle, IonCardContent, IonChip, IonLabel, IonButton, IonItem, IonInput, IonSelect, IonSelectOption, IonSegment, IonSegmentButton } from "@ionic/vue";
import { search } from "ionicons/icons";
import {useRouter} from "vue-router";
import axios from "axios";
axios.defaults.withCredentials = true;
axios.defaults.baseURL = "http://localhost/proyecto_final";

interface UserData {
  nombre: string;
  edad: string;
  correo: string;
  ubicacion: string;
}

interface Habilidad {
  habilidad_id: number;
  nombreHabilidad: string;
  descripcion: string;
  categoria: string;
  nivel: string;
}

export default defineComponent({
  components: { IonPage, IonHeader, IonToolbar, IonTitle, IonContent, IonCard, IonCardHeader, IonCardTitle, IonCardContent, IonChip, IonLabel, IonButton, IonItem, IonInput, IonSelect, IonSelectOption, IonSegment, IonSegmentButton },
  name:'MainPage',
  data() {
    return {
      userData: {} as UserData,
      habilidades: [] as Habilidad[],
      nuevaHabilidad: {
        habilidad_id: 0,
        nombreHabilidad: "",
        descripcion: "",
        categoria: "",
        nivel: ""
      } as Habilidad,
      categorias: [
        "Tecnología y electrónica",
        "Salud y bienestar",
        "Artesanía y manualidades",
        "Música y sonido",
        "Negocios y finanzas",
        "Comunicación y relaciones interpersonales",
        "Hogar y estilo de vida",
        "Deportes y actividad física",
        "Desarrollo personal y bienestar emocional",
        "Idiomas",
        "Creatividad digital",
        "Cocina y gastronomía",
        "Ecología y sostenibilidad"
      ],
      search,
      errorMessage:"",
      successMessage:"",
      habilidadesPredefinidas:[
      "Pintura", "Cocina", "Jardinería", "Costura", "Carpintería", "Programación",
    "Escritura", "Fotografía", "Dibujo", "Baile", "Idiomas", "Música", "Deporte",
    "Marketing", "Finanzas", "Arte culinario", "Diseño gráfico", "Maquillaje",
    "Yoga", "Teatro", "Edición de vídeo", "Canto", "Costura", "Fotografía",
    "Bricolaje", "Diseño web", "Cine", "Buceo", "Escalada", "Arquitectura",
    "Astrología", "Cerámica", "Ciberseguridad", "Ciencia", "Cómic",
    "Confección de ropa", "Contabilidad",
    "Cuidado de mascotas", "Cultura general", "Decoración de interiores",
    "Desarrollo de apps", "Economía", "Electricidad", "Emprendimiento", "Entrenamiento personal",
    "Escultura", "Estadística", "Filosofía",
    "Geografía", "Gestión de proyectos", "Historia", "Ilustración",
    "Inversión", "Joyería", "Literatura", "Magia", "Meditación",
    "Modelado 3D", "Nutrición", "Oratoria",
    "Paisajismo", "Papiroflexia", "Parkour", "Poesía", "Política",
    "Primeros auxilios", "Psicología", "Química", "Repostería", "Restauración de muebles",
    "Robótica", "Terapias alternativas", "Trekking",
      ]
    };
  },
  mounted() {
    this.getUserData();
    this.getUserHabilidades();
  },
  methods: {
    getUserData() {
      axios
        .get("/userdata.php")
        .then((response) => {
          this.userData = response.data.userData;
        })
        .catch((error) => {
          console.error("Error al obtener los datos del usuario:", error);
          this.errorMessage = "Error al obtener los datos del usuario. Inténtelo de nuevo más tarde.";
        });
    },
    getUserHabilidades() {
      axios
        .get("/user_habilidades.php")
        .then((response) => {
          this.habilidades = response.data.habilidades;
        })
        .catch((error) => {
          console.error("Error al obtener las habilidades del usuario:", error);
          this.errorMessage = "Error al obtener las habilidades del usuario. Inténtelo de nuevo más tarde.";
        });
    },
    agregarHabilidad() {
      if (!this.nuevaHabilidad.nombreHabilidad || !this.nuevaHabilidad.descripcion || !this.nuevaHabilidad.categoria || !this.nuevaHabilidad.nivel) {
        this.errorMessage = "Por favor, rellene todos los campos.";
        return;
      }

      axios
        .post("/agregar_habilidad.php", this.nuevaHabilidad)
        .then((response) => {
          if (response.data.success) {
            this.getUserHabilidades();
            this.nuevaHabilidad = {
              habilidad_id: 0,
              nombreHabilidad: "",
              descripcion: "",
              categoria: "",
              nivel: ""
            };
            this.errorMessage = "";
            this.successMessage = "¡Habilidad agregada con éxito!";
            setTimeout(() => { this.successMessage = "" }, 3000);
          } else {
            throw new Error(response.data.message || "Error al agregar la nueva habilidad.");
          }
        })
        .catch((error) => {
          console.error("Error al agregar la nueva habilidad:", error);
          this.errorMessage = "Error al agregar la nueva habilidad. Inténtelo de nuevo más tarde.";
        });
    },
    eliminarHabilidad(index: number) {
      const habilidadAEliminar = this.habilidades[index];
      const habilidadId = habilidadAEliminar.habilidad_id;
      this.habilidades.splice(index, 1);

      axios
        .post("/eliminar_habilidad.php", {
          habilidad_id: habilidadId
        })
        .then((response) => {
          if (response.data.success) {
            console.log("Habilidad eliminada con éxito del servidor");
            this.successMessage = "¡Habilidad eliminada con éxito!";
            setTimeout(() => { this.successMessage = "" }, 3000);
          } else {
            console.error("Error al eliminar la habilidad del servidor:", response.data.message);
            this.errorMessage = "Error al eliminar la habilidad. Inténtelo de nuevo más tarde.";
            this.habilidades.splice(index, 0, habilidadAEliminar);
          }
        })
        .catch((error) => {
          console.error("Error al eliminar la habilidad del servidor:", error);
          this.errorMessage = "Error al eliminar la habilidad. Inténtelo de nuevo más tarde.";
          this.habilidades.splice(index, 0, habilidadAEliminar);
        });
    },
    irABuscarUsuarios(){
      this.$router.push("/match");
    },
  },
});
</script>

<style scoped>
body {
  font-family: 'Arial', sans-serif;
}

.container {
  padding: 20px;
}

.user-info ion-card {
  border-radius: 10px;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.user-info ion-card-title {
  color: cadetblue;
}

.chip-container {
  display: flex;
  justify-content: flex-end;
  margin: 30px 20px 0px 0px;
}

.section-title {
  margin-top: 50px;
  margin-bottom: 15px;
  font-weight: bold;
  color: cornflowerblue;
  text-align: center;
}

.habilidad-card {
  margin-bottom: 16px;
}

.habilidad-card ion-card {
  border-radius: 10px;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.card-header-content {
  display: flex;
  align-items: center;
}

.habilidad-card ion-card-content {
  margin-top: -12px;
}

.eliminar-segment {
  --indicator-color: transparent;
  --color-checked: var(--ion-color-danger);
  justify-content: flex-end;
}

.agregar ion-card-title {
  color: darkcyan;
  padding-top: 10px;
}

.card-header-content ion-card-title {
  color: mediumpurple;
}

ion-segment-button {
  margin-left: auto;
}

.message {
  text-align: center;
  margin-top: 20px;
  font-weight: bold;
}

.error-message {
  color: red;
}

.success-message {
  color: green;
}
</style>