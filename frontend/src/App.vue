<template>
  <div>
    <h1>Simple Orders System</h1>
    <CreateOrder />
    <MyOrders />
    <Checkout />
    <OrderDetails />
  </div>
</template>

<script setup>
import CreateOrder from './components/CreateOrder.vue';
import MyOrders from './components/MyOrders.vue';
import Checkout from './components/Checkout.vue';
import OrderDetails from './components/OrderDetails.vue';
</script>
