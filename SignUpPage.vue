<template>
  <ion-page>
    <ion-header :translucent="true">
      <ion-toolbar color="secondary">
        <!-- Botón de retroceso -->
        <ion-buttons style="margin-left: 8px">
          <ion-back-button defaultHref="/home"></ion-back-button>
          <ion-title size="large">Registro</ion-title>
        </ion-buttons>
      </ion-toolbar>
    </ion-header>

    <ion-content :fullscreen="true">
      <div id="container">
        <!-- Formulario de registro -->
        <ion-item>
          <ion-input
            label="Nombre"
            label-placement="floating"
            type="text"
            v-model="txtnombre"
          ></ion-input>
        </ion-item>

        <ion-item>
          <ion-input
            label="Edad"
            label-placement="floating"
            type="number"
            v-model="txtedad"
          ></ion-input>
        </ion-item>

        <ion-item>
          <ion-input
            label="Correo electrónico"
            label-placement="floating"
            type="email"
            v-model="txtcorreo"
          ></ion-input>
        </ion-item>

        <ion-item>
          <ion-input
            label="Contraseña"
            label-placement="floating"
            type="password"
            v-model="txtpassword"
          ></ion-input>
        </ion-item>

        <ion-item>
          <ion-input
            label="Ubicación"
            label-placement="floating"
            type="text"
            v-model="txtubi"
          ></ion-input>
        </ion-item>

        <ion-button shape="round" expand="full" @click="register"
          >Enviar</ion-button
        >
        <div v-if="errorMessage" class="error-message">
          {{ errorMessage }}
        </div>
        <div v-if="successMessage" class="success-message">
          ¡Se ha registrado correctamente!
        </div>
      </div>
    </ion-content>
  </ion-page>
</template>

<script lang="ts">
import { defineComponent } from "vue";
import axios from "axios";
import { Capacitor } from "@capacitor/core";
import { env } from "@/env";
import {
  IonContent,
  IonHeader,
  IonPage,
  IonTitle,
  IonToolbar,
  IonBackButton,
  IonInput,
  IonButton,
  IonItem,
  IonButtons,
} from "@ionic/vue";

export default defineComponent({
  components: {
    IonContent,
    IonHeader,
    IonPage,
    IonTitle,
    IonToolbar,
    IonBackButton,
    IonInput,
    IonButton,
    IonItem,
    IonButtons,
  },
  data() {
    return {
      txtnombre: "",
      txtedad: "",
      txtcorreo: "",
      txtpassword: "",
      txtubi: "",
      errorMessage: "",
      successMessage: false,
    };
  },
  methods: {
    // Validar el formato del correo electrónico
    validateEmail(email: string): boolean {
      const re = /\S+@\S+\.\S+/;
      return re.test(email);
    },
    // Realizar el registro
    async register() {
      this.errorMessage = "";
      // Validación de campos obligatorios
      if (
        !this.txtnombre ||
        !this.txtedad ||
        !this.txtcorreo ||
        !this.txtpassword ||
        !this.txtubi
      ) {
        this.errorMessage = "Por favor, rellene todos los campos.";
        return;
      }
      // Validación del formato del correo electrónico
      if (!this.validateEmail(this.txtcorreo)) {
        this.errorMessage = "Introduzca un correo electrónico válido.";
        return;
      }

      try {
        // Determinar si la aplicación se está ejecutando en Android
        const isAndroid = Capacitor.isNativePlatform();
        // Determinar la URL del servidor según la plataforma
        let serverUrl = isAndroid ? env.VITE_ANDROID_SERVER_URL : env.VITE_WEB_SERVER_URL;
        serverUrl = serverUrl.replace(/:\d+$/, '');
        // Realizar solicitud HTTP con la URL del servidor determinada
        const response = await axios.post(
          serverUrl + "/proyecto_final/signup.php",
          {
            nombre: this.txtnombre,
            edad: this.txtedad,
            correo: this.txtcorreo,
            contrasena: this.txtpassword,
            ubicacion: this.txtubi,
          }
        );
        // Manejar respuesta
        if (response.data.message === "success") {
          // Limpiar campos y mostrar mensaje de éxito
          this.txtnombre = "";
          this.txtedad = "";
          this.txtcorreo = "";
          this.txtpassword = "";
          this.txtubi = "";
          this.errorMessage = "";
          this.successMessage = true;
        } else if (response.data.message === "email_exists") {
          this.errorMessage = "El correo electrónico ya existe.";
        } else {
          this.errorMessage = "¡No se pudo completar el registro!";
        }
      } catch (error) {
        console.error("Error al registrarse:", error);
        this.errorMessage = "Error inesperado. Inténtelo de nuevo más tarde.";
      }
    },
  },
});
</script>

<style scoped>
#container {
  text-align: center;
  margin-top: 50px;
}

.error-message {
  color: red;
  margin-top: 20px;
}

.success-message {
  color: green;
  margin-top: 20px;
}
</style>
