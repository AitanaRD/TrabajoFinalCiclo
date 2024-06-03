<template>
  <ion-page>
    <ion-header>
      <ion-toolbar color="secondary">
        <!-- Botón de retroceso -->
        <ion-buttons style="margin-left: 8px">
          <ion-back-button defaultHref="/main"></ion-back-button>
          <ion-title size="large">Coincidencias</ion-title>
        </ion-buttons>
      </ion-toolbar>
    </ion-header>
    <!-- Botón para redirigir a otra página -->
    <div class="chip-container">
      <ion-chip @click.prevent="irAValorar" class="eval-chip" color="warning">
        <ion-label>Valorar</ion-label>
        <ion-icon :icon="star"></ion-icon>
      </ion-chip>
    </div>
    <ion-content class="ion-padding">
      <!-- Mensajes de éxito y error -->
      <p v-if="errorMessage" class="ion-text-center error-message">
        {{ errorMessage }}
      </p>
      <p v-if="successMessage" class="ion-text-center success-message">
        {{ successMessage }}
      </p>
      <p v-if="rejectMessage" class="ion-text-center reject-message">
        {{ rejectMessage }}
      </p>
      <!-- Lista de usuarios -->
      <div v-if="usuarios.length > 0">
        <ion-card
          v-for="(usuario, index) in usuarios"
          :key="'usuario-' + index"
        >
          <ion-card-header class="card-header">
            <ion-card-title class="card-title">{{
              usuario.nombre
            }}</ion-card-title>
          </ion-card-header>
          <ion-card-content class="card-content">
            <p>Edad: {{ usuario.edad }}</p>
            <p>Habilidades: {{ usuario.habilidades.join(", ") }}</p>
            <p>Ubicación: {{ usuario.ubicacion }}</p>
            <p>Correo electrónico: {{ usuario.correo }}</p>
          </ion-card-content>
          <!-- Botones de aceptar y rechazar -->
          <div class="button-wrapper">
            <ion-button
              @click="aceptarUsuario(index)"
              class="normal-button fab-green"
            >
              <ion-icon :icon="thumbsUp" class="icon-white"></ion-icon>
            </ion-button>
            <ion-button
              @click="rechazarUsuario(index)"
              class="normal-button fab-red"
            >
              <ion-icon :icon="thumbsDown" class="icon-white"></ion-icon>
            </ion-button>
          </div>
        </ion-card>
      </div>
      <div v-else>
        <p class="ion-text-center">Cargando usuarios...</p>
      </div>
    </ion-content>
  </ion-page>
</template>

<script lang="ts">
import { defineComponent } from "vue";
import {
  IonBackButton,
  IonTitle,
  IonToolbar,
  IonLabel,
  IonPage,
  IonItem,
  IonButton,
  IonButtons,
  IonChip,
  IonHeader,
  IonContent,
  IonCard,
  IonCardHeader,
  IonCardContent,
  IonCardTitle,
} from "@ionic/vue";
import axios from "axios";
import { Capacitor } from "@capacitor/core";
import { env } from "@/env";
import { thumbsDown, thumbsUp, star } from "ionicons/icons";

interface Usuario {
  nombre: string;
  edad: number;
  habilidades: string[];
  ubicacion: string;
  correo: string;
}

export default defineComponent({
  name: "MatchPage",
  components: {
    IonBackButton,
    IonLabel,
    IonButtons,
    IonToolbar,
    IonTitle,
    IonPage,
    IonHeader,
    IonContent,
    IonCard,
    IonCardTitle,
    IonCardContent,
    IonCardHeader,
    IonChip,
    IonButton,
    IonItem,
  },
  data() {
    return {
      usuarios: [] as Usuario[],
      habilidadesUsuarioActual: [] as string[],
      correoUsuarioActual: "",
      thumbsUp,
      thumbsDown,
      star,
      errorMessage: "",
      successMessage: "",
      rejectMessage: "",
      serverUrl: "",
    };
  },
  mounted() {
    const isAndroid = Capacitor.isNativePlatform();
    this.serverUrl = isAndroid ? env.VITE_ANDROID_SERVER_URL : env.VITE_WEB_SERVER_URL;
    this.serverUrl = this.serverUrl.replace(/:\d+$/, '');
    this.obtenerUsuarios();
    this.obtenerCorreoUsuarioActual();
  },
  methods: {
    // Método para obtener el correo del usuario autenticado
    obtenerCorreoUsuarioActual() {
      axios
      .get(`${this.serverUrl}/proyecto_final/obtener_usuario_autenticado.php`)
        .then((response) => {
          if (response.data && response.data.correo) {
            this.correoUsuarioActual = response.data.correo;
          } else {
            console.error(
              "Error al obtener el correo del usuario autenticado:",
              response.data ? response.data.error : "Respuesta vacía"
            );
            this.errorMessage =
              "Error al obtener el correo del usuario autenticado. Por favor, inténtelo de nuevo más tarde.";
          }
        })
        .catch((error) => {
          console.error(
            "Error al obtener el correo del usuario autenticado:",
            error
          );
          this.errorMessage =
            "Error al obtener el correo del usuario autenticado. Por favor, inténtelo de nuevo más tarde.";
        });
    },
    // Método para obtener usuarios con habilidades similares
    obtenerUsuarios() {
  axios
    .get(`${this.serverUrl}/proyecto_final/obtener_habilidades_usuario.php`)
    .then((response) => {
      this.habilidadesUsuarioActual = response.data.habilidades.map(
        (habilidad: any) => habilidad.nombreHabilidad
      );
      let apiUrl = `${this.serverUrl}`;
      const desiredPort = '3000';
      if (this.serverUrl.includes('localhost') || this.serverUrl.includes('10.0.2.2')) {
        apiUrl += `:${desiredPort}`;
      }
      apiUrl += `/api/usuarios`;
      return axios.get<any>(apiUrl);
    })
    .then((response) => {
      const usuariosFiltrados = response.data.results
        .map((result: any) => ({
          nombre: `${result.name.first} ${result.name.last}`,
          edad: result.dob.age,
          habilidades: this.generarHabilidadesAleatorias(),
          ubicacion: `${result.location.city}, ${result.location.country}`,
          correo: `${result.email}`,
        }))
        .filter((usuario: Usuario) =>
          usuario.habilidades.some((habilidad) =>
            this.habilidadesUsuarioActual.includes(habilidad)
          )
        );
      this.usuarios = usuariosFiltrados;
    })
        .catch((error) => {
          console.error("Error al obtener usuarios o habilidades:", error);
          this.errorMessage =
            "Error al obtener usuarios o habilidades. Por favor, inténtelo de nuevo más tarde.";
        });
    },
    // Método para generar habilidades aleatorias
    generarHabilidadesAleatorias(): string[] {
      const habilidades = [
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
      ];
      const numHabilidades = Math.floor(Math.random() * 4) + 1; //Número aleatorio entre 1 y 4 (inclusive)
      const habilidadesSeleccionadas = habilidades
        .sort(() => 0.5 - Math.random())
        .slice(0, numHabilidades);
      return Array.from(new Set(habilidadesSeleccionadas));
    },
    // Método para rechazar un perfil y mostrar un mensaje
    rechazarUsuario(index: number): void {
      const usuarioRechazado = this.usuarios[index];
      this.rejectMessage = `Ha rechazado a ${usuarioRechazado.nombre}.`;
      setTimeout(() => {
        this.rejectMessage = "";
      }, 3000);
      this.usuarios.splice(index, 1);
    },
    // Método para aceptar un perfil y enviar una notificación
    aceptarUsuario(index: number): void {
      const usuarioAceptado = this.usuarios[index];
      const mensaje = `El usuario ${this.correoUsuarioActual} desea ponerse en contacto contigo.`;

      axios
      .post(`${this.serverUrl}/proyecto_final/enviar_notificacion.php`,{
          usuarioAceptado: usuarioAceptado.correo,
          mensaje: mensaje,
          correoRemitente: this.correoUsuarioActual,
        })
        .then((response) => {
          if (response.data.status === "success") {
            this.showAlertSuccess(
              `Notificación enviada con éxito a ${usuarioAceptado.nombre}`
            );
          } else {
            this.showAlertError(
              `Error al enviar la notificación: ${response.data.message}`
            );
          }
        })
        .catch((error) => {
          console.error("Error al enviar la notificación:", error);
          this.showAlertError(
            "Error al enviar la notificación. Por favor, inténtelo de nuevo más tarde."
          );
        });
      this.usuarios.splice(index, 1);
    },
    // Navegar a la EvaluationPage
    irAValorar() {
      this.$router.push("/evaluation");
    },
    // Mostrar mensaje de éxito
    showAlertSuccess(message: string) {
      this.successMessage = message;
      setTimeout(() => {
        this.successMessage = "";
      }, 3000);
    },
    // Mostrar mensaje de error
    showAlertError(message: string) {
      this.errorMessage = message;
      setTimeout(() => {
        this.errorMessage = "";
      }, 3000);
    },
  },
});
</script>

<style scoped>
ion-card {
  margin-bottom: 30px;
}
.button-wrapper {
  display: flex;
  justify-content: space-between;
  margin: 10px;
}

.normal-button {
  flex: 1;
}

.fab-green {
  --background: green;
}

.fab-red {
  --background: red;
}

.icon-white {
  color: white;
}

.success-message {
  color: green;
  font-weight: bold;
}

.error-message {
  color: red;
  font-weight: bold;
}

.reject-message {
  color: rgb(255, 115, 0);
  font-weight: bold;
}

.card-title {
  font-weight: bold;
  color: rgb(14, 101, 183);
}

.chip-container {
  display: flex;
  justify-content: flex-end;
  margin-right: 10px;
  margin-top: 10px;
}

.eval-chip {
  border: 1px solid rgb(179, 174, 174);
}
</style>
