<script setup>
import { onMounted, ref, reactive } from 'vue'
import axios from 'axios';
import Swal from 'sweetalert2';
import moment from 'moment';

import Navbar from '@/Components/Navbar.vue';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import Button from 'primevue/button';
import Dialog from 'primevue/dialog';
import Textarea from 'primevue/textarea';
import InputText from 'primevue/inputtext';
import InputNumber from 'primevue/inputnumber';
import DatePicker from 'primevue/datepicker';
import FloatLabel from 'primevue/floatlabel';
import Paginator from 'primevue/paginator';

const products  = ref(null);
const orders    = ref(null); 
const selectedOrder = reactive({
    id: null,
    date : null,
    products : []
});
const pagination_data = ref(null);
const expandedRows = ref(null);
const showOrderDialog = ref(false);

onMounted(() => {
    getOrders();
    getProducts();
});

const getOrders = async (page) => {
    page = page || 1;
    await axios.get(`/api/orders?page=${page}`)
        .then(response => {
            pagination_data.value = response.data;
            
            orders.value = response.data.data;
        })
        .catch(error => {
            console.error('Error fetching orders:', error);
        });
}

const getProducts = async () => {
    await axios.get('/api/products')
        .then(response => {
            products.value = response.data;
        })
        .catch(error => {
            console.error('Error fetching products:', error);
        });
}

const createOrder = () => {
    selectedOrder.id = null;
    selectedOrder.products = [];
    selectedOrder.date = moment().format('DD/MM/YYYY');

    products.value.forEach(product => {
        product.quantity = 0; // Reset quantity for each product
    });

    showOrderDialog.value = true;
}

const editOrder = (order) => {
    selectedOrder.id = order.id;
    selectedOrder.name = order.name;
    selectedOrder.description = order.description;
    selectedOrder.date = moment(order.date).format('DD/MM/YYYY');
    selectedOrder.products = order.products.map(product => ({
        ...product,
        quantity: product.pivot.quantity || 0 // Ensure quantity is set
    }));

    products.value = products.value.map(product => ({
        ...product,
        quantity: selectedOrder.products.find(p => p.id === product.id)?.quantity || 0 // Ensure quantity is set
    }));
    
    showOrderDialog.value = true;
}

const saveOrder = () => {
    const order = selectedOrder;
    const method = order.id ? 'patch' : 'post';
    const url = order.id ? `/api/orders/${order.id}/update` : '/api/orders';

    axios[method](url, order)
        .then(response => {
            showOrderDialog.value = false;
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: `Order ${order.id ? 'updated' : 'created'} successfully!`,
            });
            getOrders();
        })
        .catch(error => {
            showOrderDialog.value = false;
            console.error('Error saving order:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'There was an error saving the order.',
            });
        });
}

const deleteOrder = (order_id) => {
    
    Swal.fire({
        title: 'Are you sure?',
        text: `Do you want to delete the order "${order_id}"?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'No, cancel!'
    }).then((result) => {
        if (result.isConfirmed) {
            axios.delete(`/api/orders/${order_id}/delete`)
                .then(response => {
                    Swal.fire({
                        icon: 'success',
                        title: 'Deleted!',
                        text: `Order deleted successfully.`,
                    });
                    getOrders();
                })
                .catch(error => {
                    console.error('Error deleting order:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'There was an error deleting the order.',
                    });
                });
        }
    });
}

const removeProductFromOrder = (productId, order) => {
    selectedOrder = {
        ...order,
        products: order.products.filter(product => product.id !== productId)
    }

    saveOrder();
}

const closeModal = () => {
    showOrderDialog.value = false;
    selectedOrder.id = null;
    selectedOrder.products = [];
    selectedOrder.date = null;
}

</script>

<template>
    <Dialog
        v-model:visible="showOrderDialog"
        modal
        :header="selectedOrder.id ? 'Edit Order' : 'Create Order'"
        :style="{ width: '50vw' }"
    >
        <form @submit.prevent="saveOrder">
            <div class="p-field pb-8 w-100">
                <FloatLabel variant="in">
                <InputText id="name" v-model="selectedOrder.name" variant="filled" class="w-100" inputClass="w-100" />
                <label for="name">Order name</label>
                </FloatLabel>
            </div>
            <div class="p-field pb-8 w-100">
                <FloatLabel variant="in">
                <Textarea v-model="selectedOrder.description" rows="5" cols="30" />
                <label for="description">Order description</label>
                </FloatLabel>
            </div>
            <div class="p-field w-100">
                <FloatLabel variant="in">
                <DatePicker
                    v-model="selectedOrder.date"
                    id="date"
                    :showIcon="true"
                    :placeholder="'Select Date'"
                    class="w-100" 
                    format="dd/mm/yy"
                />
                <label for="date">Date</label>
                </FloatLabel>
            </div>

            <div class="p-4 pt-6">
                <h3 class="text-lg font-bold mb-4">Products</h3>
                <div style="overflow-y: scroll; max-height: 25rem; min-height: 25rem">
                    <DataTable 
                        :value="products"
                        selectionMode="multiple"
                        v-model:selection="selectedOrder.products"
                        :dataKey="'id'"
                    >
                        <Column style="width: 40%" field="name" header="Name" />
                        <Column style="width: 40%" field="price" header="Price" />
                        <Column style="width: 20%">
                            <template #body="{ data }">
                                <InputNumber 
                                    v-model="data.quantity"
                                    mode="decimal"
                                    :min="0"
                                    :showButtons="true"
                                    class="w-100"
                                    :disabled="!selectedOrder.products.includes(data)"
                                />
                            </template>
                        </Column>
                    </DataTable>
                </div>
            </div>

            <div class="flex justify-end pt-10">
                <Button label="Save" type="submit" class="p-button-success me-2" />
                <Button label="Cancel" @click="closeModal" class="p-button-secondary" />
            </div>
        </form>
    </Dialog>

    <div class="card">
        <Navbar />
        
        <h1 class="text-2xl font-bold p-4">Orders</h1>
        <DataTable
            :value="orders"
            :rows="15"
            :rowsPerPageOptions="[15]"
            dataKey="id"
            v-model:expandedRows="expandedRows"
        >
            <template #header>
                <div class="flex justify-end p-3">
                    <Button @click="createOrder">
                        <i class="pi pi-plus me-2"></i>
                        Create Order
                    </Button>
                </div>
            </template>

            <template #empty>
                <div class="text-center p-4">
                    <i class="pi pi-exclamation-triangle"></i>
                    No orders found.
                </div>
            </template>

            <template #expansion="slotProps">
                <div style="overflow-y: scroll; max-height: 25rem; min-height: 25rem">
                    <DataTable
                        :value="slotProps.data.products"
                        class="mt-4"
                    >
                        <Column>
                            <template #body=" { data }">
                                <div class="flex justify-center">
                                    <Button @click="removeProductFromOrder(data.id, slotProps.data)" class="p-button-rounded p-button-text" severity="danger">
                                        <i class="pi pi-trash"></i>
                                    </Button>
                                </div>
                            </template>
                        </Column>
                        <Column field="name" header="Name" />
                        <Column field="price" header="Price">
                            <template #body="{ data }">
                                {{ data.price.toFixed(2) }} &euro;
                            </template>
                        </Column>

                        <Column field="pivot.quantity" header="Quantity" />

                    </DataTable>
                </div>
            </template>

            <Column expander />
            <Column>
                <template #body="{ data }">
                    <Button @click="editOrder(data)" class="p-button-rounded p-button-text">
                        <i class="pi pi-pencil"></i>
                    </Button>
                    <Button @click="deleteOrder(data.id)" class="p-button-rounded p-button-text" severity="danger" >
                        <i class="pi pi-trash me-2"></i>
                    </Button>
                </template>
            </Column>
            <Column field="name" header="Name" />
            <Column field="description" header="Description" />
            <Column field="date" header="Date">
                <template #body="{ data}">
                    {{ moment(data.date).format('DD/MM/YYYY') }}
                </template>
            </Column>
            <Column header="Total">
                <template #body="{ data }">
                    {{ data.products.reduce((total, product) => total + (product.price * (product.quantity || 1)), 0).toFixed(2) }} &euro;
                </template>
            </Column>
        </DataTable>
        <Paginator
            :rows="15"
            :totalRecords="pagination_data?.total || 0"
            @page="getOrders($event.page + 1)"
        />
    </div>
</template>
