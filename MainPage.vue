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

      <!-- Botón para redirigir a otra página -->
      <div class="chip-container">
        <ion-chip @click="irABuscarUsuarios" class="search-chip" color="medium">
          <ion-icon :icon="search"></ion-icon>
          <ion-label>Buscar usuarios</ion-label>
        </ion-chip>
      </div>
      <div class="container">
        <!-- Información del usuario -->
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
        <!-- Lista de habilidades del usuario -->
        <div
          v-for="(habilidad, index) in habilidades"
          :key="index"
          class="habilidad-card"
        >
          <ion-card>
            <ion-card-header>
              <div class="card-header-content">
                <ion-card-title>{{ habilidad.nombreHabilidad }}</ion-card-title>
                <!-- Botón para eliminar -->
                <ion-segment
                  value="default"
                  class="eliminar-segment"
                  color="danger"
                >
                  <ion-segment-button
                    value="eliminar"
                    @click="eliminarHabilidad(index)"
                  >
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
        <!-- Formulario para agregar nueva habilidad -->
        <ion-card class="agregar">
          <ion-card-header>
            <!-- Mensajes de error y éxito -->
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
              <!-- Seleccionar el nombre -->
              <ion-item>
                <ion-label position="fixed">Nombre:</ion-label>
                <ion-select
                  placeholder="Seleccione una opción"
                  v-model="nuevaHabilidad.nombreHabilidad"
                  interface="action-sheet"
                  cancel-text="Cancelar"
                >
                  <ion-select-option
                    v-for="habilidad in habilidadesPredefinidas"
                    :key="habilidad"
                    :value="habilidad"
                    >{{ habilidad }}</ion-select-option
                  >
                </ion-select>
              </ion-item>
              <!-- Seleccionar la descripción -->
              <ion-item>
                <ion-label position="fixed">Descripción:</ion-label>
                <ion-input
                  placeholder="Breve descripción..."
                  v-model="nuevaHabilidad.descripcion"
                ></ion-input>
              </ion-item>
              <!-- Seleccionar la categoría -->
              <ion-item>
                <ion-label position="fixed">Categoría:</ion-label>
                <ion-select
                  placeholder="Seleccione una opción"
                  v-model="nuevaHabilidad.categoria"
                  interface="alert"
                  cancel-text="Cancelar"
                >
                  <ion-select-option
                    v-for="categoria in categorias"
                    :key="categoria"
                    :value="categoria"
                    >{{ categoria }}</ion-select-option
                  >
                </ion-select>
              </ion-item>
              <!-- Seleccionar el nivel -->
              <ion-item>
                <ion-label position="fixed">Nivel:</ion-label>
                <ion-select
                  placeholder="Seleccione una opción"
                  v-model="nuevaHabilidad.nivel"
                  interface="popover"
                >
                  <ion-select-option value="principiante"
                    >Principiante</ion-select-option
                  >
                  <ion-select-option value="intermedio"
                    >Intermedio</ion-select-option
                  >
                  <ion-select-option value="avanzado"
                    >Avanzado</ion-select-option
                  >
                </ion-select>
              </ion-item>
              <!-- Botón para enviar el formulario -->
              <ion-button expand="block" type="submit" color="primary"
                >Agregar</ion-button
              >
            </form>
          </ion-card-content>
        </ion-card>
      </div>
    </ion-content>
  </ion-page>
</template>

<script lang="ts">
import { defineComponent } from "vue";
import {
  IonPage,
  IonHeader,
  IonToolbar,
  IonTitle,
  IonContent,
  IonCard,
  IonCardHeader,
  IonCardTitle,
  IonCardContent,
  IonChip,
  IonLabel,
  IonButton,
  IonItem,
  IonInput,
  IonSelect,
  IonSelectOption,
  IonSegment,
  IonSegmentButton,
} from "@ionic/vue";
import { search } from "ionicons/icons";
import axios from "axios";
import { Capacitor } from "@capacitor/core";
import { env } from "@/env";
axios.defaults.withCredentials = true;

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
  components: {
    IonPage,
    IonHeader,
    IonToolbar,
    IonTitle,
    IonContent,
    IonCard,
    IonCardHeader,
    IonCardTitle,
    IonCardContent,
    IonChip,
    IonLabel,
    IonButton,
    IonItem,
    IonInput,
    IonSelect,
    IonSelectOption,
    IonSegment,
    IonSegmentButton,
  },
  name: "MainPage",
  data() {
    //Devuelve un objeto con los datos (reactivos) del componente
    return {
      userData: {} as UserData,
      habilidades: [] as Habilidad[],
      nuevaHabilidad: {
        habilidad_id: 0,
        nombreHabilidad: "",
        descripcion: "",
        categoria: "",
        nivel: "",
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
        "Ecología y sostenibilidad",
      ],
      search,
      errorMessage: "",
      successMessage: "",
      habilidadesPredefinidas: [
        "Pintura",
        "Cocina",
        "Jardinería",
        "Costura",
        "Carpintería",
        "Programación",
        "Escritura",
        "Fotografía",
        "Dibujo",
        "Baile",
        "Idiomas",
        "Música",
        "Deporte",
        "Marketing",
        "Finanzas",
        "Arte culinario",
        "Diseño gráfico",
        "Maquillaje",
        "Yoga",
        "Teatro",
        "Edición de vídeo",
        "Canto",
        "Costura",
        "Fotografía",
        "Bricolaje",
        "Diseño web",
        "Cine",
        "Buceo",
        "Escalada",
        "Arquitectura",
        "Astrología",
        "Cerámica",
        "Ciberseguridad",
        "Ciencia",
        "Cómic",
        "Confección de ropa",
        "Contabilidad",
        "Cuidado de mascotas",
        "Cultura general",
        "Decoración de interiores",
        "Desarrollo de apps",
        "Economía",
        "Electricidad",
        "Emprendimiento",
        "Entrenamiento personal",
        "Escultura",
        "Estadística",
        "Filosofía",
        "Geografía",
        "Gestión de proyectos",
        "Historia",
        "Ilustración",
        "Inversión",
        "Joyería",
        "Literatura",
        "Magia",
        "Meditación",
        "Modelado 3D",
        "Nutrición",
        "Oratoria",
        "Paisajismo",
        "Papiroflexia",
        "Parkour",
        "Poesía",
        "Política",
        "Primeros auxilios",
        "Psicología",
        "Química",
        "Repostería",
        "Restauración de muebles",
        "Robótica",
        "Terapias alternativas",
        "Trekking",
      ],
      serverUrl: "",
    };
  },
  mounted() {
    const isAndroid = Capacitor.isNativePlatform();
    this.serverUrl = isAndroid
      ? env.VITE_ANDROID_SERVER_URL
      : env.VITE_WEB_SERVER_URL;
      this.serverUrl = this.serverUrl.replace(/:\d+$/, '');
    this.getUserData(); //Cargar datos del usuario al montar el componente
    this.getUserHabilidades(); //Cargar habilidades del usuario al montar el componente
  },
  methods: {
    //  Método para obtener los datos desde el servidor
    getUserData() {
      axios
      .get(`${this.serverUrl}/proyecto_final/userdata.php`)
        .then((response) => {
          this.userData = response.data.userData;
        })
        .catch((error) => {
          console.error("Error al obtener los datos del usuario:", error);
          this.errorMessage =
            "Error al obtener los datos del usuario. Inténtelo de nuevo más tarde.";
        });
    },
    // Método para obtener las habilidades del usuario desde el servidor
    getUserHabilidades() {
      axios
      .get(`${this.serverUrl}/proyecto_final/user_habilidades.php`)
        .then((response) => {
          this.habilidades = response.data.habilidades;
        })
        .catch((error) => {
          console.error("Error al obtener las habilidades del usuario:", error);
          this.errorMessage =
            "Error al obtener las habilidades del usuario. Inténtelo de nuevo más tarde.";
        });
    },
    // Método para agregar una nueva habilidad
    agregarHabilidad() {
      // Validar los campos estén completos
      if (
        !this.nuevaHabilidad.nombreHabilidad ||
        !this.nuevaHabilidad.descripcion ||
        !this.nuevaHabilidad.categoria ||
        !this.nuevaHabilidad.nivel
      ) {
        this.errorMessage = "Por favor, rellene todos los campos.";
        return;
      }
      // Enviar solicitud al servidor
      axios.post(`${this.serverUrl}/proyecto_final/agregar_habilidad.php`, this.nuevaHabilidad)
        .then((response) => {
          if (response.data.success) {
            this.getUserHabilidades(); // Actualizar la lista de habilidades
            this.nuevaHabilidad = {
              //Restablecer los campos
              habilidad_id: 0,
              nombreHabilidad: "",
              descripcion: "",
              categoria: "",
              nivel: "",
            };
            this.errorMessage = "";
            this.successMessage = "¡Habilidad agregada con éxito!";
            setTimeout(() => {
              this.successMessage = "";
            }, 3000);
          } else {
            // Manejar errores de respuesta del servidor
            throw new Error(
              response.data.message || "Error al agregar la nueva habilidad."
            );
          }
        })
        .catch((error) => {
          // Manejar errores de solicitud
          console.error("Error al agregar la nueva habilidad:", error);
          this.errorMessage =
            "Error al agregar la nueva habilidad. Inténtelo de nuevo más tarde.";
        });
    },
    //Método para eliminar una habilidad
    eliminarHabilidad(index: number) {
      //Obtener la habilidad a eliminar junto con ID
      const habilidadAEliminar = this.habilidades[index];
      const habilidadId = habilidadAEliminar.habilidad_id;
      this.habilidades.splice(index, 1); //Elimina la habilidad de la lista
      //Enviar solicitud al servidor
      axios.post(`${this.serverUrl}/proyecto_final/eliminar_habilidad.php`, {
          habilidad_id: habilidadId,
        })
        .then((response) => {
          if (response.data.success) {
            console.log("Habilidad eliminada con éxito del servidor");
            this.successMessage = "¡Habilidad eliminada con éxito!";
            setTimeout(() => {
              this.successMessage = "";
            }, 3000);
          } else {
            // Manejar errores de respuesta del servidor
            console.error(
              "Error al eliminar la habilidad del servidor:",
              response.data.message
            );
            this.errorMessage =
              "Error al eliminar la habilidad. Inténtelo de nuevo más tarde.";
            this.habilidades.splice(index, 0, habilidadAEliminar); // Restaurar la habilidad en caso de error
          }
        })
        .catch((error) => {
          console.error("Error al eliminar la habilidad del servidor:", error);
          this.errorMessage =
            "Error al eliminar la habilidad. Inténtelo de nuevo más tarde.";
          this.habilidades.splice(index, 0, habilidadAEliminar);
        });
    },
    // Método para redirigir a MatchPage
    irABuscarUsuarios() {
      this.$router.push("/match");
    },
  },
});
</script>

<style scoped>
body {
  font-family: "Arial", sans-serif;
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
