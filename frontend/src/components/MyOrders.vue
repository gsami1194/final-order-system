<template>
  <div>
    <h2>My Orders</h2>
    <div v-if="orders.length === 0">No orders found.</div>
    <ul v-else>
      <li v-for="order in orders" :key="order.id">
        <strong>Order #{{ order.id }}</strong> - {{ order.status }}<br />
        Name: {{ order.customer_name }} | Email: {{ order.email }}<br />
        Shipping: {{ order.shipping_method }} | Date: {{ order.created_at }}
      </li>
    </ul>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'

const orders = ref([])

onMounted(async () => {
  const res = await fetch('http://localhost:8000/backend/api/get_orders.php')
  orders.value = await res.json()
})
</script>

<style scoped>
li {
  margin-bottom: 1rem;
  padding: 0.5rem;
  border-bottom: 1px solid #ccc;
}
</style>
