<template>
  <div>
    <h2>Create New Order</h2>
    <form @submit.prevent="submitOrder">
      <label>
        Product:
        <select v-model="selectedProduct">
          <option v-for="product in products" :key="product.id" :value="product.id">
            {{ product.name }} - ${{ product.price }}
          </option>
        </select>
      </label>

      <label>
        Quantity:
        <input type="number" v-model="quantity" min="1" required />
      </label>

      <label>
        Customer Name:
        <input type="text" v-model="customerName" required />
      </label>

      <label>
        Email:
        <input type="email" v-model="email" required />
      </label>

      <label>
        Shipping Method:
        <select v-model="shippingMethod" required>
          <option value="pickup">Pickup</option>
          <option value="delivery">Delivery</option>
        </select>
      </label>

      <button type="submit">Submit Order</button>
    </form>
    <p v-if="message">{{ message }}</p>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'

const products = ref([])
const selectedProduct = ref('')
const quantity = ref(1)
const customerName = ref('')
const email = ref('')
const shippingMethod = ref('pickup')
const message = ref('')

onMounted(async () => {
  const res = await fetch('http://localhost:8000/backend/api/get_products.php')
  products.value = await res.json()
  if (products.value.length > 0) selectedProduct.value = products.value[0].id
})

const submitOrder = async () => {
  const orderData = {
    customer_name: customerName.value,
    email: email.value,
    shipping_method: shippingMethod.value,
    items: [{
      product_id: selectedProduct.value,
      quantity: quantity.value
    }]
  }

  const res = await fetch('http://localhost:8000/backend/api/create_order.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify(orderData)
  })

  const result = await res.json()
  message.value = result.message || 'Order submitted successfully!'
}
</script>

<style scoped>
form {
  display: flex;
  flex-direction: column;
  max-width: 400px;
}
label {
  margin-bottom: 10px;
}
</style>
