<template>
  <div>
    <h2>Checkout</h2>
    <form @submit.prevent="startPayment">
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
      <button type="submit">Pay Now</button>
    </form>

    <div v-if="iframeHTML" v-html="iframeHTML"></div>
  </div>
</template>

<script setup>
import { ref } from 'vue'

const customerName = ref('')
const email = ref('')
const shippingMethod = ref('pickup')
const iframeHTML = ref('')

const startPayment = async () => {
  const payload = {
    customer_name: customerName.value,
    email: email.value,
    shipping_method: shippingMethod.value
  }

  const res = await fetch('http://localhost:8000/backend/api/start_payment.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify(payload)
  })

  const result = await res.json()
  iframeHTML.value = result.iframe || '<p>Unable to load payment form</p>'
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
