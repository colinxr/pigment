<template>
  <div class="conversation-card card space-between align-middle p-4 h-20">
    <div class="avatar w-10">
      <div class="rounded-full bg-slate-600 ">
        <div class="w-10 h-full flex items-center justify-center">
          <span class="text-xl font-bold">CR</span>
        </div>
      </div>
    </div>
    <div class="card-body grow ml-5">
      <span class="card-title p">Alex Nino Gheciu</span>
      <p class="text-sm">Click the button to watch on Jetflix app.</p>
      <div class="card-actions justify-end">
      </div>
    </div>
  </div>
</template>

<script setup></script>

<style>
.card {
  flex-direction: row;
}

.card:hover {
  @apply bg-slate-200;
}

.card-body {
  padding: 0;
  gap: 0;
}

.card-title {
  font-size: 1rem;
}
</style>