<script setup>
import { onMounted, ref } from 'vue'
import axios from 'axios';

import Navbar from '@/Components/Navbar.vue';

</script>

<template>
  <div class="card">
    <Navbar />
    <div class="flex justify-center items-center h-screen">
      <h1 class="text-4xl font-bold">Welcome to the Orders Page</h1>
    </div>
  </div>
</template>
