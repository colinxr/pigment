<template>
  <div>
    <div class="container mx-auto flex justify-center items-center">
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
  </div>
</template>

<script setup>
import { ref, reactive, } from '../.nuxt/imports';
import ApiService from '@dayplanner/ApiService'

const apiService = new ApiService();

const email = ref('colinxr@gmail.com')
const password = ref('Wksc202adsfdayp')
const errorState = reactive({
  isSet: false,
  message: '',
  validationErrs: null,
})

const handleResponseErrors = ({ status, data }) => {
  const newErrorState = {
    isSet: true,
    message: data.message,
    validationErrs: null,
  }

  if (status === 422) {
    newErrorState.validationErrs = data.errors
  }

  Object.assign(errorState, newErrorState)
}

const handleSubmit = async () => {
  try {
    const response = await apiService.auth.login({
      email: email.value,
      password: password.value
    })


    console.log(response.status)


    if (response.status !== 200) {
      console.log('got here');
      handleResponseErrors(response)
    }

    // move to next page

  } catch (error) {
    error.isSet = true
    error.message = 'something went wrong'
  }
}
</script>