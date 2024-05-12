import { createRouter, createWebHistory } from '@ionic/vue-router';
import { RouteRecordRaw } from 'vue-router';
import HomePage from '../views/HomePage.vue';
import MainPage from '../views/MainPage.vue';
import SchPage from '../views/SchPage.vue';
import SignUpPage from '../views/SignUpPage.vue';
import Inicio from '../views/Inicio.vue';

const routes: Array<RouteRecordRaw> = [
  {
    path: '/',
    redirect: '/inicio'
  },
  {
    path: '/inicio',
    name: 'Inicio',
    component: Inicio
  },
  {
    path: '/home',
    name: 'Home',
    component: HomePage
  },
  {
    path: '/main',
    name: 'Main',
    component: MainPage
  },
  {
    path: '/search',
    name: 'Search',
    component: SchPage
  },
  {
    path: '/signup',
    name: 'SignUp',
    component: SignUpPage
  },
]

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes
})

export default router;