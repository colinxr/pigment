import { ref } from 'vue'
import { defineStore } from 'pinia'
import { ModalStateI, VueComponent } from './types'

const useModalStore = defineStore("modalStore", () => {
  const intialState = { component: null, props: {} };

  const state = ref<ModalStateI>(intialState)

  const openModal = (payload: {component: VueComponent, props: Object }) => {
    const {component, props } = payload 
  
    state.value.component = component
    state.value.props = props || {}
  }

  const closeModal = () => {
    state.value = intialState
  }

  return {
    state,
    openModal,
    closeModal,
  }
});

export default useModalStore