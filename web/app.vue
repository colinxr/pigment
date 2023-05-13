<template>
  <div>
    <input name="email" type="text" :value="form.email" />
    <input name="password" type="password" :value="form.password" />
    <button type="submit" @click="handleSubmit">Login</button>
  </div>
</template>

<script setup>
import { reactive } from './.nuxt/imports';
import ApiService from '../shared/ApiService'

const apiService = new ApiService();

const form = reactive({
  email: 'colinxr@gmail.com',
  password: 'Wksc202pass',
})


const handleSubmit = async () => {
  const { email, password } = form
  
  const response = await apiService.auth.login({ email, password })

  console.log(response)
}
</script>