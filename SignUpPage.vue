<template>
  <ion-page>
    <ion-header :translucent="true">
      <ion-toolbar color="secondary">
        <ion-buttons>
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

        <ion-button shape="round" expand="full" href="/home" @click="register">Enviar</ion-button>
      </div>
    </ion-content>
  </ion-page>
</template>

<script lang="ts">
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
  IonLabel,
} from "@ionic/vue";
import { defineComponent } from "vue";
import axios from "axios";

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
    IonButtons,
    IonItem,
    IonLabel,
  },
  data() {
    return {
      txtnombre: "",
      txtedad: "",
      txtcorreo: "",
      txtpassword: "",
      txtubi: "",
    };
  },
  methods: {
    register() {
      if (this.txtnombre === "") {
        alert("Please input a valid name!");
      } else if (this.txtedad === "") {
        alert("Please input a valid age!");
      } else if (this.txtcorreo === "") {
        alert("Please input a valid email!");
      } else if (this.txtpassword === "") {
        alert("Please input a valid password!");
      } else if (this.txtubi === "") {
        alert("Please input a valid location!");
      } else {
        axios
          .post("http://localhost/proyecto_final/signup.php", null, {
            params: {
              nombre: this.txtnombre,
              edad: this.txtedad,
              correo: this.txtcorreo,
              contrasena: this.txtpassword,
              ubicacion: this.txtubi,
            },
          })
          .then((response) => {
            if (response.data.message === "success") {
              this.txtnombre = "";
              this.txtedad = "";
              this.txtcorreo = "";
              this.txtpassword = "";
              this.txtubi = "";
              alert("¡Registro guardado!");
            } else {
              alert("¡Registro no guardado!");
            }
          })
          .catch((error) => {
            console.error("Error al registrarse:", error);
            alert("Error inesperado. Inténtelo de nuevo más tarde.");
          });
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
</style>