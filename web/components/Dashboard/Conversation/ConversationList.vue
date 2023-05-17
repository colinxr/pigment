<template>
  <div class="conversations-list grow p-2">
    <header>
      <h1>Messages</h1>
    </header>
    <main>
      <ConversationCard v-for="i in 5" :key="i" />
    </main>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import ConversationCard from './ConversationCard.vue'
import ApiService from '@dayplanner/ApiService'

onBeforeMount(async () => {
  const user = await ApiService.auth.getAuthenticatedSession()
  console.log(user);

  const res = await ApiService.submissions.index()
  console.log(res);
});


const { pending, data: conversations } = useAsyncData('conversations', () => {


  return
})

console.log(conversations);
</script>
