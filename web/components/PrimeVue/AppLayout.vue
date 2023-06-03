<script setup>
import { computed, watch, ref } from 'vue'
import useLayout from '@/composables/useLayout'
import AppTopbar from '@/components/PrimeVue/AppTopbar.vue'
import AppFooter from '@/components/PrimeVue/AppFooter.vue'
import AppSidebar from '@/components/PrimeVue/AppSidebar.vue'
import AppConfig from '@/components/PrimeVue/AppConfig.vue'

const { layoutConfig, layoutState, isSidebarActive } = useLayout()
const outsideClickListener = ref(null)

watch(isSidebarActive, (newVal) => {
  if (newVal) {
    bindOutsideClickListener()
  } else {
    unbindOutsideClickListener()
  }
})
const containerClass = computed(() => ({
  'layout-theme-light': layoutConfig.darkTheme.value === 'light',
  'layout-theme-dark': layoutConfig.darkTheme.value === 'dark',
  'layout-overlay': layoutConfig.menuMode.value === 'overlay',
  'layout-static': layoutConfig.menuMode.value === 'static',
  'layout-static-inactive': layoutState.staticMenuDesktopInactive.value && layoutConfig.menuMode.value === 'static',
  'layout-overlay-active': layoutState.overlayMenuActive.value,
  'layout-mobile-active': layoutState.staticMenuMobileActive.value,
  'p-input-filled': layoutConfig.inputStyle.value === 'filled',
  'p-ripple-disabled': !layoutConfig.ripple.value
}))

const bindOutsideClickListener = () => {
  if (!outsideClickListener.value) {
    outsideClickListener.value = (event) => {
      if (isOutsideClicked(event)) {
        layoutState.overlayMenuActive.value = false
        layoutState.staticMenuMobileActive.value = false
        layoutState.menuHoverActive.value = false
      }
    }

    document.addEventListener('click', outsideClickListener.value)
  }
}

const unbindOutsideClickListener = () => {
  if (outsideClickListener.value) {
    document.removeEventListener('click', outsideClickListener)
    outsideClickListener.value = null
  }
}

const isOutsideClicked = (event) => {
  const sidebarEl = document.querySelector('.layout-sidebar')
  const topbarEl = document.querySelector('.layout-menu-button')

  return !(sidebarEl.isSameNode(event.target) || sidebarEl.contains(event.target) || topbarEl.isSameNode(event.target) || topbarEl.contains(event.target))
}
</script>

<template>
  <div class="layout-wrapper" :class="containerClass">
    <app-topbar />
    <div class="layout-sidebar">
      <app-sidebar />
    </div>
    <div class="layout-main-container">
      <div class="layout-main">
        <router-view />
      </div>
      <app-footer />
    </div>
    <app-config />
    <div class="layout-mask" />
  </div>
</template>

<style lang="scss" scoped></style>
