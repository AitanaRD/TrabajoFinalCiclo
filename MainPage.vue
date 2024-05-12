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
        <ion-chip @click="irABuscarUsuarios" class="search-chip" color="secondary">
          <ion-icon :icon="search"></ion-icon>
          <ion-label>Buscar usuarios</ion-label>
        </ion-chip>
      </div>
      <div class="container">
        <div class="user-info">
          <ion-card>
            <ion-card-header>
              <ion-card-title color="secondary">{{
                userData.nombre
              }}</ion-card-title>
            </ion-card-header>
            <ion-card-content>
              <p><strong>Edad:</strong> {{ userData.edad }}</p>
              <p><strong>Correo electrónico:</strong> {{ userData.correo }}</p>
              <p><strong>Ubicación:</strong> {{ userData.ubicacion }}</p>
            </ion-card-content>
          </ion-card>
        </div>

        <h3 class="section-title">Mis habilidades</h3>
        <ion-list lines="full">
          <ion-item v-for="(habilidad, index) in habilidades" :key="index" class="skill-item">
            <ion-label>{{ habilidad.nombreHabilidad }} - {{ habilidad.descripcion }} -
              {{ habilidad.categoria }} -
              {{ habilidad.tipoIntercambio }}</ion-label>
            <ion-button @click="eliminarHabilidad(index)" fill="clear" color="danger">Eliminar</ion-button>
          </ion-item>
        </ion-list>

        <ion-card class="ion-padding-top">
          <ion-card-header>
            <ion-card-title>Agregar nueva habilidad:</ion-card-title>
          </ion-card-header>
          <ion-card-content>
            <form @submit.prevent="agregarHabilidad()">
              <ion-item>
                <ion-input label="Nombre de habilidad" label-placement="floating"
                  v-model="nuevaHabilidad.nombreHabilidad" required></ion-input>
              </ion-item>

              <ion-item>
                <ion-input label="Descripción" label-placement="floating" v-model="nuevaHabilidad.descripcion"
                  required></ion-input>
              </ion-item>

              <ion-item>
                <ion-select label="Categoría" label-placement="floating" v-model="nuevaHabilidad.categoria"
                  interface="popover">
                  <ion-select-option v-for="categoria in categorias" :key="categoria" :value="categoria">{{ categoria
                    }}</ion-select-option>
                </ion-select>
              </ion-item>

              <ion-item>
                <ion-select label="Tipo de intercambio" label-placement="floating"
                  v-model="nuevaHabilidad.tipoIntercambio" interface="popover">
                  <ion-select-option value="aprender">Aprender</ion-select-option>
                  <ion-select-option value="enseñar">Enseñar</ion-select-option>
                </ion-select>
              </ion-item>
              <ion-button expand="block" type="submit" color="tertiary">Agregar</ion-button>
            </form>
          </ion-card-content>
        </ion-card>
      </div>
    </ion-content>
  </ion-page>
</template>

<script lang="ts">
import { defineComponent } from "vue";
import { useRoute } from "vue-router";
import {
  IonButton,
  IonContent,
  IonHeader,
  IonInput,
  IonItem,
  IonLabel,
  IonList,
  IonPage,
  IonTextarea,
  IonTitle,
  IonToolbar,
  IonSelect,
  IonSelectOption,
  IonIcon,
  IonChip,
} from "@ionic/vue";
import axios from "axios";
import { search } from "ionicons/icons";
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
  tipoIntercambio: string;
}

export default defineComponent({
  components: {
    IonButton,
    IonContent,
    IonHeader,
    IonInput,
    IonItem,
    IonLabel,
    IonList,
    IonPage,
    IonTextarea,
    IonTitle,
    IonToolbar,
    IonSelect,
    IonSelectOption,
    IonChip,
    IonIcon,
  },
  data() {
    return {
      userData: {} as UserData,
      habilidades: [] as Habilidad[],
      nuevaHabilidad: {
        habilidad_id: 0,
        nombreHabilidad: "",
        descripcion: "",
        categoria: "",
        tipoIntercambio: "",
      } as Habilidad,
      categorias: [
        "Tecnología y electrónica",
        "Salud y bienestar",
        "Arte y creatividad",
        "Educación y aprendizaje",
        "Negocios y finanzas",
        "Comunicación y relaciones interpersonales",
        "Hogar y estilo de vida",
        "Deportes y actividad física",
        "Desarrollo personal y bienestar emocional",
        "Idiomas",
      ],
      search,
    };
  },
  mounted() {
    this.getUserData();
    this.getUserHabilidades();
  },
  methods: {
    getUserData() {
      axios
        .get("http://localhost/proyecto_final/userdata.php")
        .then((response) => {
          this.userData = response.data.userData;
        })
        .catch((error) => {
          console.error("Error al obtener los datos del usuario:", error);
        });
    },
    getUserHabilidades() {
      axios
        .get("http://localhost/proyecto_final/user_habilidades.php")
        .then((response) => {
          this.habilidades = response.data.habilidades;
        })
        .catch((error) => {
          console.error("Error al obtener las habilidades del usuario:", error);
        });
    },
    agregarHabilidad() {
      axios
        .post(
          "http://localhost/proyecto_final/agregar_habilidad.php",
          this.nuevaHabilidad
        )
        .then((response) => {
          if (response.data.success) {
            this.getUserHabilidades();
            this.nuevaHabilidad = {
              habilidad_id: 0,
              nombreHabilidad: "",
              descripcion: "",
              categoria: "",
              tipoIntercambio: "",
            };
          } else {
            console.error(
              "Error al agregar la nueva habilidad:",
              response.data.message
            );
          }
        })
        .catch((error) => {
          console.error("Error al agregar la nueva habilidad:", error);
        });
    },
    eliminarHabilidad(index: number) {
      const habilidadAEliminar = this.habilidades[index];
      const habilidadId = habilidadAEliminar.habilidad_id;
      this.habilidades.splice(index, 1);

      axios
        .post("http://localhost/proyecto_final/eliminar_habilidad.php", {
          habilidad_id: habilidadId,
        })
        .then((response) => {
          if (response.data.success) {
            console.log("Habilidad eliminada exitosamente del servidor");
          } else {
            console.error(
              "Error al eliminar la habilidad del servidor:",
              response.data.message
            );
            this.habilidades.splice(index, 0, habilidadAEliminar);
          }
        })
        .catch((error) => {
          console.error("Error al eliminar la habilidad del servidor:", error);
          this.habilidades.splice(index, 0, habilidadAEliminar);
        });
    },
    irABuscarUsuarios() {
      this.$router.push("/search");
    },
  },
});
</script>

<style scoped>
.chip-container {
  position: absolute;
  top: 60px;
  right: 20px;
  z-index: 999;
}

.search-chip {
  border: 1px solid #333;
}

.container {
  padding: 20px;
}

.user-info ion-card {
  margin-top: 40px;
}

.user-info h3 {
  font-size: 24px;
  margin-bottom: 10px;
  font-weight: bold;
}

.user-info p {
  margin-bottom: 5px;
}

.skill-item {
  padding: 10px;
  border-bottom: 1px solid #ccc;
}

.skill-item ion-label {
  font-weight: bold;
}

.skill-item ion-button {
  --padding-start: 0;
}

ion-select {
  width: 100%;
}

.section-title {
  margin-top: 40px;
  font-weight: bold;
  color: #665693;
  text-align: center;
}

.search-chip:hover {
  background-color: #ddd;
}
</style>
