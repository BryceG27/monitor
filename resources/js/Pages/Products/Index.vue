<script setup>
import { onMounted, ref, reactive } from 'vue'
import axios from 'axios';
import Swal from 'sweetalert2';

import Navbar from '@/Components/Navbar.vue';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import Button from 'primevue/button';
import Dialog from 'primevue/dialog';
import InputText from 'primevue/inputtext';
import InputNumber from 'primevue/inputnumber';
import FloatLabel from 'primevue/floatlabel';

const products = ref([]);
const selectedProduct = reactive({
  id: null,
  name: '',
  price: 0
});

const showProductDialog = ref(false);

onMounted(() => {
  getProducts();
})

const getProducts = async () => {
  try {
    const response = await axios.get('/api/products');
    products.value = response.data;
  } catch (error) {
    console.error('Error fetching products:', error);
  }
}

const createProduct = () => {
  showProductDialog = true
  selectedProduct = { 
    id: null, 
    name: '', 
    price: 0 
  }
}

const saveProduct = () => {
  const product = selectedProduct.value;

  const method = product.id ? 'patch' : 'post';
  const url = product.id ? `/api/products/${product.id}/update` : '/api/products';

  axios[method](url, product)
    .then(response => {
      showProductDialog.value = false;
      Swal.fire({
        icon: 'success',
        title: 'Success',
        text: `Product ${product.id ? 'updated' : 'created'} successfully!`,
      });
      getProducts();
    })
    .catch(error => {
      showProductDialog.value = false;
      console.error('Error saving product:', error);
      Swal.fire({
        icon: 'error',
        title: 'Error',
        html : `<div>
                  <ul>
                    ${error.response.data.errors ? Object.values(error.response.data.errors).map(err => `<li>${err[0]}</li>`).join('') : 'An unexpected error occurred.'}
                  </ul>
                </div>`,
      });
    });
}

const deleteProduct = () => {
  if (!selectedProduct.id) {
    Swal.fire({
      icon: 'warning',
      title: 'No Product Selected',
      text: 'Please select a product to delete.',
    });
  }

  Swal.fire({
    'icon' : 'info',
    'title' : 'Are you sure?',
    'text' : 'This action cannot be undone.',
    'showCancelButton' : true,
    'confirmButtonColor' : '#d33',
    'confirmButtonText' : 'Yes, delete it!',
    'cancelButtonText' : 'No, cancel!',
  }).then((result) => {
    if (result.isConfirmed) {
      axios.delete(`/api/products/${selectedProduct.id}/delete`)
        .then(response => {
          Swal.fire({
            icon: 'success',
            title: 'Deleted',
            text: 'Product deleted successfully!',
          });
          getProducts();
          selectedProduct.id = null; // Reset selected product
        })
        .catch(error => {
          console.error('Error deleting product:', error);
          Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Failed to delete product.',
          });
        });
    }
  })
}

</script>

<template>
  <Dialog 
    v-model:visible="showProductDialog"
    modal
    :header="selectedProduct.id ? 'Edit Product' : 'Create Product'"
    :style="{ width: '35vw' }"
  >
    <form @submit.prevent="saveProduct">
      <div class="p-field pb-8 w-100">
        <FloatLabel variant="in">
          <InputText id="name" v-model="selectedProduct.name" variant="filled" class="w-100" inputClass="w-100" />
          <label for="name">Product name</label>
        </FloatLabel>
      </div>
      <div class="p-field w-100">
        <FloatLabel variant="in">
          <InputNumber 
            v-model="selectedProduct.price"
            mode="currency"
            currency="USD"
            locale="en-US"
            :min="0"
            :showButtons="true"
            class="w-100"
          />
          <label for="price">Price</label>
        </FloatLabel>
      </div>
      <div class="flex justify-end pt-10">
        <Button label="Save" type="submit" class="p-button-success me-2" />
        <Button label="Cancel" @click="showProductDialog = false" class="p-button-secondary" />
      </div>
    </form>
  </Dialog>

  <div class="card">
    <Navbar />
    
    <h1 class="text-2xl font-bold p-4">Products</h1>
    <DataTable 
      :value="products"
    >
      <template #header>
        <div class="flex justify-end p-3">
          <Button @click="createProduct" class="p-button-success me-3">
            <i class="pi pi-plus me-2"></i>
            Create Product
          </Button>
        </div>
      </template>
      <Column style="width: 10%" field="id">
        <template #body="{ data }">
          <div class="flex justify-between">
            <Button @click="selectedProduct = data, showProductDialog = true " class="p-button-rounded p-button-text">
              <i class="pi pi-pencil"></i>
            </Button>
            <Button @click="deleteProduct(data.id)" class="p-button-rounded p-button-text" severity="danger" >
              <i class="pi pi-trash me-2"></i>
            </Button>
          </div>
        </template>
      </Column>
      <Column style="width: 45%" field="name" header="Name" />
      <Column style="width: 45%" field="price" header="Price">
        <template #body="{ data }">
          {{ data.price.toFixed(2) }} &euro;
        </template> 
      </Column>
    </DataTable>
  </div>
</template>
