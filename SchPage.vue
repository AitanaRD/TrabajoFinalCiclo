<template>
  <ion-page>
    <ion-header :translucent="true">
      <ion-toolbar color="secondary">
        <ion-buttons>
          <ion-back-button defaultHref="/main"></ion-back-button>
          <ion-title size="large">Buscar usuarios</ion-title>
        </ion-buttons>
      </ion-toolbar>
    </ion-header>

    <ion-content class="ion-padding" :fullscreen="true">
      <div id="container">
        <div class="filter-section">
          <h2 class="title">Búsqueda por habilidad</h2>
          <ion-searchbar v-model="searchQuery" placeholder="Buscar habilidad..."
            class="ion-margin-bottom"></ion-searchbar>
          <ion-segment color="secondary" v-model="selectedExchangeTypeHabilidad" class="ion-margin-bottom">
            <ion-segment-button value="enseñar">
              <ion-label>Enseñar</ion-label>
            </ion-segment-button>
            <ion-segment-button value="aprender">
              <ion-label>Aprender</ion-label>
            </ion-segment-button>
          </ion-segment>
          <ion-button @click="buscarPorHabilidad" expand="block" class="search-button"
            color="tertiary">Buscar</ion-button>
        </div>

        <div v-if="usuariosHabilidad.length > 0">
          <h2 class="title">Resultados de búsqueda por habilidad</h2>
          <ion-list class="user-list">
            <ion-item v-for="(usuario, index) in usuariosHabilidad" :key="'habilidad-' + index" class="user-item">
              <ion-label>{{ usuario.nombre }}</ion-label>
              <p>Edad: {{ usuario.edad }}</p>
              <p>Ubicación: {{ usuario.ubicacion }}</p>
              <p>Correo: {{ usuario.correo }}</p>
              <ion-button @click="abrirModal(usuario.correo)" fill="clear">
                <ion-icon :icon="mailOutline"></ion-icon>
              </ion-button>
            </ion-item>
          </ion-list>
        </div>
        <div v-else>
          <p class="no-results-message">
            No se encontraron usuarios que coincidan con los criterios de búsqueda.
          </p>
        </div>

        <div class="filter-section">
          <h2 class="title">Filtrar por categoría</h2>
          <div class="chip-wrapper">
            <ion-chip v-for="categoria in categorias" :key="categoria" @click="selectCategory(categoria)"
              :class="{ selected: selectedCategory === categoria }" class="chip">
              <ion-icon :icon="iconos[categoria]" :color="colores[categoria]" class="icon"></ion-icon>
              <ion-label>{{ categoria }}</ion-label>
            </ion-chip>
          </div>
        </div>

        <ion-segment color="secondary" v-model="selectedExchangeTypeCategoria" class="ion-margin-bottom">
          <ion-segment-button value="enseñar">
            <ion-label>Enseñar</ion-label>
          </ion-segment-button>
          <ion-segment-button value="aprender">
            <ion-label>Aprender</ion-label>
          </ion-segment-button>
        </ion-segment>

        <ion-button @click="filtrarPorCategoria" expand="block" class="search-button"
          color="tertiary">Filtrar</ion-button>

        <div v-if="usuariosCategoria.length > 0">
          <h2 class="title">Resultados de filtrado por categoría</h2>
          <ion-list class="user-list">
            <ion-item v-for="(usuario, index) in usuariosCategoria" :key="'categoria-' + index" class="user-item">
              <ion-label>{{ usuario.nombre }}</ion-label>
              <p>Edad: {{ usuario.edad }}</p>
              <p>Ubicación: {{ usuario.ubicacion }}</p>
              <p>Correo: {{ usuario.correo }}</p>
              <ion-button @click="abrirModal(usuario.correo)" fill="clear">
                <ion-icon :icon="mailOutline"></ion-icon>
              </ion-button>
            </ion-item>
          </ion-list>
        </div>
        <div v-else>
          <p class="no-results-message">
            No se encontraron usuarios que coincidan con los criterios de búsqueda.
          </p>
        </div>
      </div>

      <!-- Componente Modal para el formulario de solicitud -->
      <ion-modal :is-open="mostrarModal" @closed="cerrarModal()">
        <ion-header>
          <ion-toolbar color="secondary">
            <ion-buttons>
              <ion-button @click="cerrarModal()">
                <ion-icon :icon="close"></ion-icon>
              </ion-button>
            </ion-buttons>
            <ion-title>Enviar Solicitud de Intercambio</ion-title>
          </ion-toolbar>
        </ion-header>
        <ion-content>
          <ion-item>
            <ion-label position="stacked">Detalles</ion-label>
            <ion-textarea v-model="detalles" :rows=4></ion-textarea>
          </ion-item>
          <ion-item>
            <ion-label position="stacked">Fecha</ion-label>
            <ion-datetime v-model="fecha" display-format="D MMM YYYY" min="2000-01-01" max="2030-12-31"></ion-datetime>
          </ion-item>
          <ion-button @click="enviarSolicitud()" expand="block" color="tertiary">Enviar</ion-button>
        </ion-content>
      </ion-modal>
    </ion-content>
  </ion-page>
</template>


<script lang="ts">
import {
  IonButton,
  IonContent,
  IonHeader,
  IonItem,
  IonLabel,
  IonList,
  IonPage,
  IonSearchbar,
  IonSegment,
  IonSegmentButton,
  IonTitle,
  IonIcon,
  IonChip,
  IonBackButton,
  IonModal,
  IonTextarea,
  IonDatetime,
  IonToolbar
} from "@ionic/vue";
import { defineComponent } from "vue";
import axios from "axios";
import {
  hardwareChipOutline,
  medkit,
  brush,
  school,
  briefcase,
  chatboxEllipses,
  home,
  fitness,
  heart,
  language,
  close,
  mailOutline,
  sendOutline
} from "ionicons/icons";
axios.defaults.withCredentials = true;
axios.defaults.baseURL = "http://localhost/proyecto_final";

interface Usuario {
  nombre: string;
  edad: string;
  ubicacion: string;
  correo: string;
  tipoIntercambio: string;
}
interface Iconos {
  [categoria: string]: string;
}
interface Colores {
  [categoria: string]: string;
}

export default defineComponent({
  components: {
    IonButton,
    IonContent,
    IonHeader,
    IonItem,
    IonLabel,
    IonList,
    IonPage,
    IonSearchbar,
    IonSegment,
    IonSegmentButton,
    IonTitle,
    IonIcon,
    IonChip,
    IonBackButton,
    IonModal,
    IonTextarea,
    IonDatetime,
    IonToolbar
  },
  setup() {
    return {
      mailOutline,
      sendOutline,
      close
    };
  },
  data() {
    return {
      searchQuery: "",
      selectedCategory: "",
      selectedExchangeTypeHabilidad: "enseñar",
      selectedExchangeTypeCategoria: "enseñar",
      usuariosHabilidad: [] as Usuario[],
      usuariosCategoria: [] as Usuario[],
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
      iconos: {
        "Tecnología y electrónica": hardwareChipOutline,
        "Salud y bienestar": medkit,
        "Arte y creatividad": brush,
        "Educación y aprendizaje": school,
        "Negocios y finanzas": briefcase,
        "Comunicación y relaciones interpersonales": chatboxEllipses,
        "Hogar y estilo de vida": home,
        "Deportes y actividad física": fitness,
        "Desarrollo personal y bienestar emocional": heart,
        Idiomas: language,
      } as Iconos,
      colores: {
        "Tecnología y electrónica": "primary",
        "Salud y bienestar": "secondary",
        "Arte y creatividad": "primary",
        "Educación y aprendizaje": "secondary",
        "Negocios y finanzas": "secondary",
        "Comunicación y relaciones interpersonales": "secondary",
        "Hogar y estilo de vida": "secondary",
        "Deportes y actividad física": "danger",
        "Desarrollo personal y bienestar emocional": "danger",
        Idiomas: "primary",
      } as Colores,
      mostrarModal: false,
      detalles: "",
      fecha: "",
      correoDestino: "",
    };
  },
  methods: {
    selectCategory(category: string) {
      this.selectedCategory = category;
    },
    buscarPorHabilidad() {
      if (this.searchQuery) {
        const tipoIntercambio =
          this.selectedExchangeTypeHabilidad === "enseñar"
            ? "enseñar"
            : "aprender";
        axios
          .get(
            `http://localhost/proyecto_final/buscar_habilidad.php?habilidad=${this.searchQuery}&tipo_intercambio=${tipoIntercambio}`
          )
          .then((response) => {
            this.usuariosHabilidad = response.data.usuarios;
            this.usuariosCategoria = [];
          })
          .catch((error) => {
            console.error("Error al buscar usuarios por habilidad:", error);
          });
      }
    },
    filtrarPorCategoria() {
      if (this.selectedCategory && this.selectedExchangeTypeCategoria) {
        const tipoIntercambio =
          this.selectedExchangeTypeCategoria === "enseñar"
            ? "enseñar"
            : "aprender";
        const params = new URLSearchParams();
        params.append("categoria", this.selectedCategory);
        params.append("tipo_intercambio", tipoIntercambio);
        const url = `http://localhost/proyecto_final/filtrar_categoria.php?${params.toString()}`;
        axios
          .get(url)
          .then((response) => {
            this.usuariosCategoria = response.data.usuarios;
            this.usuariosHabilidad = [];
          })
          .catch((error) => {
            console.error("Error al filtrar usuarios por categoría:", error);
          });
      }
    },
    abrirModal(correoDestino: string) {
      this.correoDestino = correoDestino;
      this.mostrarModal = true;
    },
    cerrarModal() {
      this.mostrarModal = false;
    },
    enviarSolicitud() {
    const solicitud = {
    detalles: this.detalles,
    fecha: this.fecha.split('T')[0],
    correoDestino: this.correoDestino
  };
  axios.post('http://localhost/proyecto_final/enviar_solicitud.php', solicitud)
    .then(response => {
      console.log(response.data);
    })
    .catch(error => {
      console.error('Error al enviar la solicitud:', error);
    })
    .finally(() => {
      this.cerrarModal();
    });
    }
  }
}
);
</script>

<style scoped>
#container {
  max-width: 600px;
  margin: 0 auto;
}

.filter-section {
  margin-bottom: 20px;
}

.title {
  font-size: 1.2em;
  margin-bottom: 10px;
}

.search-button {
  margin-top: 10px;
}

.user-item {
  display: flex;
  justify-content: space-between;
}

.user-item ion-label {
  margin-right: 10px;
}

.user-item p {
  margin-right: 20px;
}

.no-results-message {
  text-align: center;
  color: #888;
}

.chip-container {
  display: flex;
  flex-wrap: wrap;
}

.chip {
  margin: 5px;
  padding: 5px 10px;
  border-radius: 20px;
  background-color: #e0e0e0;
  cursor: pointer;
}

.chip.selected {
  background-color: #3880ff;
  color: white;
}
</style>