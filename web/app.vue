<template>
  <div>
    <NuxtWelcome />
  </div>
</template>

<script setup>
import ApiService from '../shared/ApiService'

const api = new ApiService();

console.log(api)
</script>