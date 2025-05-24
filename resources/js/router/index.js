import { createRouter, createWebHistory } from 'vue-router'

import Home from '@/Pages/Home.vue'
import Login from '@/Pages/Auth/Login.vue'
import Register from '@/Pages/Auth/Register.vue'
import Products from '@/Pages/Products/Index.vue'
import Orders from '@/Pages/Orders/Index.vue'

const routes = [
  { path: '/', name: 'home', component: Home },
  { path: '/login', name: 'login', component: Login },
  { path: '/register', name: 'register', component: Register },
  { path: '/orders', name: 'orders', component: Orders },
  { path: '/products', name: 'products', component: Products },
]

const router = createRouter({
  history: createWebHistory(),
  routes,
})

export default router
