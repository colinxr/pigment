<script setup>
  import { computed } from "vue"
  import useModalStore from "@/stores/modal"
  import { getReadableDate } from "@/services/dateService"
  import AppointmentDeleteModal from "@/components/Modal/AppointmentDeleteModal.vue"

  const modalStore = useModalStore()

  const props = defineProps({
    appointment: {
      type: Object,
      required: true,
    },
    status: {
      type: String,
      default: "active",
    },
  })

  const showControls = ref(false)

  const dateTime = computed(() =>
    getReadableDate(props.appointment.startDateTime)
  )

  const handleDelete = () => {
    modalStore.openModal({
      component: markRaw(AppointmentDeleteModal),
      props: { appointment: props.appointment },
    })
  }

  const handleEdit = () =>
    navigateTo(`/appointments/${props.appointment.id}/edit`)

  const handleMouseEnter = () => {
    if (props.status === "past") return
    showControls.value = true
  }

  const handleMouseLeave = () => {
    if (props.status === "past") return
    showControls.value = false
  }
</script>

<template>
  <nuxt-link :to="`/appointments/${props.appointment.id}/`">
    <div
      class="appt-card relative mb-2"
      @mouseenter="handleMouseEnter"
      @mouseleave="handleMouseLeave"
    >
      <div
        class="text-sm font-medium"
        :class="{ 'line-through ': props.status !== 'active' }"
      >
        {{ dateTime }}
      </div>
      <div class="text-xs truncate w-40">
        {{ props.appointment.name }}
      </div>
      <div class="text-xs truncate w-40">
        {{ props.appointment.description }}
      </div>

      <div v-if="showControls" class="controls absolute right-0 top-0 p-1">
        <button @click.prevent="handleEdit" class="mr-2">
          <i class="pi pi-pencil"></i>
        </button>

        <button @click.prevent="handleDelete">
          <i class="pi pi-times"></i>
        </button>
      </div>
    </div>
  </nuxt-link>
</template>
