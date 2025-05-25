<script setup>
import { ref } from 'vue';
import Menubar from 'primevue/menubar';

const items = ref([
    {
        label: 'Home',
        icon: 'pi pi-home',
        route: '/'
    },
    {
        label: 'Products',
        icon: 'pi pi-box',
        route: '/products'
    },
    {
        label: 'Orders',
        icon: 'pi pi-shopping-cart',
        route: '/orders'
    },
]);
</script>

<template>
    <Menubar :model="items">
      <template #item="{ item, props }">
        <router-link v-if="item.route" v-slot="{ href, navigate }" :to="item.route" custom>
          <a :href="href" v-bind="props.action" @click="navigate">
            <span :class="item.icon" />
            <span v-text="item.label" />
          </a>
        </router-link>
      </template>
    </Menubar>
</template>