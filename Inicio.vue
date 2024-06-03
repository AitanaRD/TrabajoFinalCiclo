<template>
  <ion-page>
    <ion-header> </ion-header>
    <ion-content style="--background: #fffdd0">
      <!-- Contenedor para el logo y el título de la aplicación -->
      <div class="logo-container">
        <img src="/assets/logo.png" alt="Logo" class="logo" />
        <h1 class="title">SKILLCONNECT</h1>
      </div>
    </ion-content>
  </ion-page>
</template>

<script lang="ts">
// Importación de componentes
import { defineComponent } from "vue";
import { useRouter } from "vue-router";

// Definición de componentes
export default defineComponent({
  name: "Inicio",
  setup() {
    const router = useRouter();

    // Redirige al usuario tras 5 segundos
    setTimeout(() => {
      router.push("/home");
    }, 5000);

    return {};
  },
});
</script>

<style scoped>
/* Estilos aplicables a este componente */
.logo-container {
  display: flex;
  /* Flexbox para centrar contenido */
  flex-direction: column;
  justify-content: center;
  /* Centra el contenido verticalmente */
  align-items: center;
  /* Centra el contenido horizontalmente */
  height: 100%;
}

.logo {
  width: 200px;
  height: auto;
}

.title {
  font-size: 3rem;
  font-family: "Trebuchet MS", "Lucida Sans Unicode", "Lucida Grande",
    "Lucida Sans", Arial, sans-serif, cursive;
  margin-top: 80px;
  color: #733b98;
}
</style>
