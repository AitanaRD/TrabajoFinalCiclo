<template>
  <ion-page>
    <ion-header>
      <ion-toolbar color="secondary">
        <ion-title size="large">Match de habilidades</ion-title>
      </ion-toolbar>
    </ion-header>
    <div class="chip-container">
      <ion-chip @click.prevent="irAValorar" class="eval-chip" color="warning">
        <ion-label>Valorar</ion-label>
        <ion-icon :icon="star"></ion-icon>
      </ion-chip>
    </div>
    <ion-content class="ion-padding">
      <div v-if="usuarios.length > 0">
        <ion-card v-for="(usuario, index) in usuarios" :key="'usuario-' + index">
          <ion-card-header>
            <ion-card-title>{{ usuario.nombre }}</ion-card-title>
            <ion-card-subtitle>Edad: {{ usuario.edad }}</ion-card-subtitle>
          </ion-card-header>
          <ion-card-content class="card-content">
            <p>Habilidades: {{ usuario.habilidades.join(', ') }}</p>
            <p>Ubicación: {{ usuario.ubicacion }}</p>
            <p>Correo electrónico: {{ usuario.correo }}</p>
          </ion-card-content>
          <div class="button-wrapper">
            <ion-button @click="aceptarUsuario(index)" class="normal-button fab-green">
              <ion-icon :icon="thumbsUp" class="icon-white"></ion-icon>
            </ion-button>
            <ion-button @click="rechazarUsuario(index)" class="normal-button fab-red">
              <ion-icon :icon="thumbsDown" class="icon-white"></ion-icon>
            </ion-button>
          </div>
        </ion-card>
      </div>
      <div v-else>
        <p class="ion-text-center">Cargando usuarios...</p>
        <p v-if="errorMessage" class="ion-text-center error-message">{{ errorMessage }}</p>
      </div>
    </ion-content>
  </ion-page>
</template>


<script lang="ts">
import { defineComponent } from 'vue';
import axios from "axios";
import { useRouter } from "vue-router";
import { thumbsDown, thumbsUp, star } from 'ionicons/icons';

interface Usuario {
  nombre: string;
  edad: number;
  habilidades: string[];
  ubicacion: string;
  correo: string;
}

export default defineComponent({
  name: "TinderHabilidades",
  data() {
    return {
      usuarios: [] as Usuario[],
      habilidadesUsuarioActual: [] as string[],
      correoUsuarioActual: '',
      thumbsUp,
      thumbsDown,
      star,
      errorMessage: ''
    };
  },
  mounted() {
    this.obtenerUsuarios();
    this.obtenerCorreoUsuarioActual();
  },
  methods: {
    obtenerCorreoUsuarioActual() {
      axios.get("/obtener_usuario_autenticado.php")
        .then(response => {
          if (response.data && response.data.correo) {
            this.correoUsuarioActual = response.data.correo;
          } else {
            console.error('Error al obtener el correo del usuario autenticado:', response.data ? response.data.error : 'Respuesta vacía');
            this.errorMessage = 'Error al obtener el correo del usuario autenticado. Por favor, inténtelo de nuevo más tarde.';
          }
        })
        .catch((error) => {
          console.error('Error al obtener el correo del usuario autenticado:', error);
          this.errorMessage = 'Error al obtener el correo del usuario autenticado. Por favor, inténtelo de nuevo más tarde.';
        });
    },
    obtenerUsuarios() {
      axios.get("/obtener_habilidades_usuario.php")
        .then(response => {
          this.habilidadesUsuarioActual = response.data.habilidades.map((habilidad: any) => habilidad.nombreHabilidad);
          return axios.get<any>("http://localhost:3000/api/usuarios");
        })
        .then((response) => {
          const usuariosFiltrados = response.data.results
            .map((result: any) => ({
              nombre: `${result.name.first} ${result.name.last}`,
              edad: result.dob.age,
              habilidades: this.generarHabilidadesAleatorias(),
              ubicacion: `${result.location.city}, ${result.location.country}`,
              correo: `${result.email}`
            }))
            .filter((usuario: Usuario) =>
              usuario.habilidades.some(habilidad => this.habilidadesUsuarioActual.includes(habilidad))
            );
          this.usuarios = usuariosFiltrados;
        })
        .catch((error) => {
          console.error("Error al obtener usuarios o habilidades:", error);
          this.errorMessage = 'Error al obtener usuarios o habilidades. Por favor, inténtelo de nuevo más tarde.';
        });
    },
    generarHabilidadesAleatorias(): string[] {
      const habilidades = [
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
      ];
      const numHabilidades = Math.floor(Math.random() * 4) + 1; //Número aleatorio entre 1 y 4 (inclusive)
      const habilidadesSeleccionadas = habilidades.sort(() => 0.5 - Math.random()).slice(0, numHabilidades);
      return Array.from(new Set(habilidadesSeleccionadas));
    },
    rechazarUsuario(index: number): void {
      this.usuarios.splice(index, 1);
    },
    aceptarUsuario(index: number): void {
      const usuarioAceptado = this.usuarios[index];
      const mensaje = `El usuario ${this.correoUsuarioActual} desea ponerse en contacto contigo.`;

      axios.post("/enviar_notificacion.php", {
        usuarioAceptado: usuarioAceptado.correo,
        mensaje: mensaje,
        correoRemitente: this.correoUsuarioActual
      })
        .then((response) => {
          if (response.data.status === 'success') {
            this.showAlert(`Notificación enviada con éxito a ${usuarioAceptado.nombre}`);
          } else {
            this.showAlert(`Error al enviar la notificación: ${response.data.message}`);
          }
        })
        .catch((error) => {
          console.error('Error al enviar la notificación:', error);
          this.showAlert('Error al enviar la notificación. Por favor, inténtelo de nuevo más tarde.');
        });

      this.usuarios.splice(index, 1);
    },
    showAlert(message: string) {
      window.alert(message);
    },
    irAValorar() {
      this.$router.push("/evaluation");
    },
  }
});
</script>


<style scoped>
.card-content {
  margin-bottom: 20px;
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

.error-message {
  color: red;
  font-weight: bold;
}

.chip-container {
  display: flex;
  justify-content: flex-end;
  margin: 20px 20px 0px 0px;
}

.eval-chip {
  border: 1px solid grey;
}
</style>