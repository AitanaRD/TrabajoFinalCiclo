<template>
  <ion-app>
    <ion-router-outlet></ion-router-outlet>
  </ion-app>
  <ion-page>
    <ion-header :translucent="true">
      <ion-toolbar color="secondary">
        <ion-title size="large">Login</ion-title>
      </ion-toolbar>
    </ion-header>

    <ion-content :fullscreen="true">
      <ion-header collapse="condense">
        <ion-toolbar>
          <ion-title>Login</ion-title>
        </ion-toolbar>
      </ion-header>

      <div id="container">
        <ion-item>
          <ion-icon :icon="personCircleOutline"></ion-icon>
          <ion-input type="text" v-model="txtusername" placeholder="Usuario"></ion-input>
        </ion-item>
        <ion-item>
          <ion-icon :icon="lockClosedOutline"></ion-icon>
          <ion-input type="password" v-model="txtpassword" placeholder="Contraseña"></ion-input>
        </ion-item>
        <ion-button shape="round" expand="full" @click="login">Iniciar sesión</ion-button>
        <ion-button shape="round" href="/signup" expand="full" color="medium" fill="outline">Registrarse</ion-button>
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
    return {
      txtusername: "",
      txtpassword: "",
    };
  },
  setup() {
    return {
      personCircleOutline,
      lockClosedOutline,
    };
  },
  methods: {
    login() {
      if (this.txtusername == "") {
        alert("Please input a valid username.");
      } else if (this.txtpassword == "") {
        alert("Please input a valid password.");
      } else {
        axios
          .post("http://localhost/proyecto_final/login.php", null, {
            params: {
              nombre: this.txtusername,
              contrasena: this.txtpassword,
            },
          })
          .then((response) => {
            if (response.data.message == "success") {
              this.$router.push("/main");
            } else {
              alert("¡Usuario o contraseña inválidos!");
            }
          })
          .catch((error) => {
            alert(
              error.message || "Error inseperado. Inténtelo de nuevo más tarde."
            );
          });
      }
    },
  },
});
</script>

<style scoped>
#container {
  text-align: center;
  position: absolute;
  left: 0;
  right: 0;
  top: 50%;
  transform: translateY(-50%);
}

#container strong {
  font-size: 20px;
  line-height: 26px;
}

#container p {
  font-size: 16px;
  line-height: 22px;

  color: #8c8c8c;

  margin: 0;
}

#container a {
  text-decoration: none;
}
</style>
