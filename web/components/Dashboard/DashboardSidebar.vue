<template>
  <div class="sidebar flex bg-white">
    <DashboardNav />
    <ConversationList />
  </div>
</template>

<script setup>
import DashboardNav from './DashboardNav';
import ConversationList from './Conversation/ConversationList'
</script>