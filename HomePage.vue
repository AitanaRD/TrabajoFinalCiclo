<template>
  <ion-app>
    <ion-router-outlet></ion-router-outlet>
  </ion-app>
  <ion-page>
    <ion-header :translucent="true">
      <ion-toolbar color="secondary">
        <ion-title size="large">Inicio sesión</ion-title>
      </ion-toolbar>
    </ion-header>

    <ion-content :fullscreen="true">
      <ion-header collapse="condense">
        <ion-toolbar>
          <ion-title>Inicio sesión</ion-title>
        </ion-toolbar>
      </ion-header>

      <div id="container">
        <!-- Campo de entrada de usuario -->
        <ion-item class="custom-item">
          <ion-icon :icon="personCircleOutline"></ion-icon>
          <ion-input
            type="text"
            v-model="txtusername"
            placeholder="Usuario"
          ></ion-input>
        </ion-item>
        <!-- Campo de entrada de contraseña -->
        <ion-item class="custom-item">
          <ion-icon :icon="lockClosedOutline"></ion-icon>
          <ion-input
            type="password"
            v-model="txtpassword"
            placeholder="Contraseña"
          ></ion-input>
        </ion-item>
        <!-- Botón de inicio de sesión -->
        <ion-button class="custom-button" shape="round" @click="login"
          >Iniciar sesión</ion-button
        >
        <!-- Botón de registro -->
        <ion-button
        class="custom-button"
          shape="round"
          href="/signup"
          expand="full"
          color="medium"
          fill="outline"
          >Registrarse</ion-button
        >
        <!-- Mensaje de error -->
        <div v-if="errorMessage" class="error-message">
          {{ errorMessage }}
        </div>
      </div>
    </ion-content>
  </ion-page>
</template>

<script lang="ts">
import {
  IonContent,
  IonHeader,
  IonIcon,
  IonPage,
  IonTitle,
  IonToolbar,
  IonInput,
  IonButton,
  IonItem,
} from "@ionic/vue";
import { defineComponent } from "vue";
import { personCircleOutline, lockClosedOutline } from "ionicons/icons";
import axios from "axios";
import { Capacitor } from "@capacitor/core";
import { env } from "@/env";

export default defineComponent({
  components: {
    IonContent,
    IonHeader,
    IonPage,
    IonTitle,
    IonToolbar,
    IonIcon,
    IonInput,
    IonButton,
    IonItem,
  },
  data() {
    // Determinar si la aplicación se está ejecutando en Android
    const isAndroid = Capacitor.isNativePlatform();
      // Determinar la URL del servidor según la plataforma
      let serverUrl = isAndroid ? env.VITE_ANDROID_SERVER_URL : env.VITE_WEB_SERVER_URL;
      serverUrl = serverUrl.replace(/:\d+$/, '');
    return {
      // Almacena los datos ingresados
      txtusername: "",
      txtpassword: "",
      errorMessage: "",
      serverUrl: serverUrl,
    };
  },
  setup() {
    return {
      //Inicializar
      personCircleOutline,
      lockClosedOutline,
    };
  },
  methods: {
    // Realizar el inicio de sesión
    login() {
      this.errorMessage = "";
      // Validación de campos
      if (this.txtusername == "") {
        this.errorMessage = "Por favor, ingrese un nombre de usuario válido.";
      } else if (this.txtpassword == "") {
        this.errorMessage = "Por favor, ingrese una contraseña válida.";
      } else {
        // Realizar la solicitud al servidor
        axios
        .post(`${this.serverUrl}/proyecto_final/login.php`, null, {
          params: {
              nombre: this.txtusername,
              contrasena: this.txtpassword,
          },
          })
          .then((response) => {
            // Manejar la respuesta del servidor
            if (response.data.message == "success") {
              // Redirigir a MainPage si no hay error
              this.$router.push("/main");
            } else {
              // Mostrar mensaje de error si las credenciales son incorrectas
              this.errorMessage = "¡Usuario o contraseña incorrectos!";
            }
          })
          .catch((error) => {
            // Manejar errores de la solicitud
            this.errorMessage =
              error.message ||
              "Error inesperado. Inténtelo de nuevo más tarde.";
          });
      }
    },
  },
});
</script>

<style scoped>
.custom-button,
.custom-item {
  max-width: 400px; /* Aumenta el ancho máximo */
  width: 100%; /* Hace que el elemento ocupe todo el ancho hasta el máximo */
  margin: 0 auto 1rem; /* Centra el elemento y agrega un margen inferior */
}

#container {
  display: flex;
  flex-direction: column;
  align-items: center; /* Centra el contenido horizontalmente */
  justify-content: center; /* Centra el contenido verticalmente */
  height: 100vh; /* Hace que el contenedor ocupe toda la altura de la pantalla */
  padding: 16px; /* Agrega un poco de espacio alrededor del contenido */
}

ion-item.custom-item {
  --min-height: 44px; /* Establece la altura mínima de los campos de entrada */
}

ion-icon {
  margin-right: 8px; /* Agrega un margen derecho a los iconos para separarlos del texto */
}

.error-message {
  color: red;
  text-align: center;
  margin-top: 1rem;
}
</style>