<template>
  <div class="flex justify-center items-center">
    <form class="mx-auto w-1/2" @submit.prevent="handleSubmit">
      <div class="form-control content-center w-full max-w-xs">
        <label class="label" for="email">
          <span class="label-text">email</span>
        </label>
        <input class="input input-bordered w-full max-w-xs" name="email" type="text" v-model="email" />
      </div>

      <div class="form-control">
        <label class="label" for="password">
          <span class="label-text">password</span>
        </label>
        <input class="input input-bordered w-full max-w-xs" name="password" type="password" v-model="password" />
      </div>

      <button class="btn mt-5" type="submit">Login</button>

      <div v-if="errorState.isSet" class="alert alert-error shadow-lg w-full max-w-xs mt-5">
        <div>
          <span>{{ errorState.message }}</span>
        </div>
      </div>
    </form>
  </div>

  <div>
    <button class="btn mt-5" type="submit" @click="getUser">Get User</button>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import ApiService from '@dayplanner/apiservice'

import useAuthStore from '@/stores/auth'

const store = useAuthStore()
const { errorState, handleResponseErrors } = useFormErrors()

const email = ref('')
const password = ref('')

const getUser = async () => {
  const res = await ApiService.auth.getAuthenticatedSession()
}

const handleSubmit = async () => {
  try {
    const response = await ApiService.auth.login({
      email: email.value,
      password: password.value
    })

    if (response.status !== 200) {
      return handleResponseErrors(response)
    }

    store.setUser(response.data.user)

    return navigateTo('/')

  } catch (error) {
    errorState.isSet = true
    errorState.message = 'something went wrong'
  }

  definePageMeta({ middleware: 'user-is-authenticated' })
}
</script>