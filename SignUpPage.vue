<template>
  <ion-page>
    <ion-header :translucent="true">
      <ion-toolbar color="secondary">
        <ion-buttons style="margin-left: 8px;">
          <ion-back-button defaultHref="/home"></ion-back-button>
          <ion-title size="large">Registro</ion-title>
        </ion-buttons>
      </ion-toolbar>
    </ion-header>

    <ion-content :fullscreen="true">
      <div id="container">
        <ion-item>
          <ion-input label="Nombre" label-placement="floating" type="text" v-model="txtnombre"></ion-input>
        </ion-item>

        <ion-item>
          <ion-input label="Edad" label-placement="floating" type="number" v-model="txtedad"></ion-input>
        </ion-item>

        <ion-item>
          <ion-input label="Correo electrónico" label-placement="floating" type="email" v-model="txtcorreo"></ion-input>
        </ion-item>

        <ion-item>
          <ion-input label="Contraseña" label-placement="floating" type="password" v-model="txtpassword"></ion-input>
        </ion-item>

        <ion-item>
          <ion-input label="Ubicación" label-placement="floating" type="text" v-model="txtubi"></ion-input>
        </ion-item>

        <ion-button shape="round" expand="full" @click="register">Enviar</ion-button>
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
    validateEmail(email: string): boolean {
      const re = /\S+@\S+\.\S+/;
      return re.test(email);
    },
    async register() {
      this.errorMessage = "";
      if (!this.txtnombre || !this.txtedad || !this.txtcorreo || !this.txtpassword || !this.txtubi) {
        this.errorMessage = "Por favor, rellene todos los campos.";
        return;
      }
      if (!this.validateEmail(this.txtcorreo)) {
        this.errorMessage = "Introduzca un correo electrónico válido.";
        return;
      }

      try {
        const response = await axios.post("http://localhost/proyecto_final/signup.php", {
          nombre: this.txtnombre,
          edad: this.txtedad,
          correo: this.txtcorreo,
          contrasena: this.txtpassword,
          ubicacion: this.txtubi,
        });

        if (response.data.message === "success") {
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