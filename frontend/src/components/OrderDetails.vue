<template>
  <div>
    <h2>Order Details</h2>
    <label>
      Enter Order ID:
      <input type="number" v-model="orderId" @keyup.enter="fetchDetails" />
    </label>
    <button @click="fetchDetails">Load Details</button>

    <div v-if="payment">
      <h3>Payment</h3>
      <p><strong>Status:</strong> {{ payment.status }}</p>
      <pre><strong>Request:</strong> {{ payment.request_payload }}</pre>
      <pre><strong>Response:</strong> {{ payment.response_payload }}</pre>
    </div>

    <div v-if="refunds.length">
      <h3>Refund History</h3>
      <ul>
        <li v-for="refund in refunds" :key="refund.id">
          <pre><strong>Request:</strong> {{ refund.request_payload }}</pre>
          <pre><strong>Response:</strong> {{ refund.response_payload }}</pre>
        </li>
      </ul>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'

const orderId = ref('')
const payment = ref(null)
const refunds = ref([])

const fetchDetails = async () => {
  const res = await fetch(`http://localhost:8000/backend/api/order_details.php?order_id=${orderId.value}`)
  const data = await res.json()
  payment.value = data.payment || null
  refunds.value = data.refunds || []
}
</script>

<style scoped>
pre {
  background-color: #f4f4f4;
  padding: 10px;
  overflow-x: auto;
}
</style>
