import { ref } from 'vue'
import { defineStore } from 'pinia'
import { ModalStateI, VueComponent } from './types';

const useModalStore =  defineStore("modal-store", () => {
  const intialState = { component: null, props: {} };

  const modalState = ref<ModalStateI>(intialState)

  const openModal = (payload: {component: VueComponent, props: Object }) => {
    const {component, props } = payload 
    modalState.value.component = component
    modalState.value.props = props || {}
  }

  const closeModal = () => {
    modalState.value = intialState
  }

  return {
    modalState,
    openModal,
    closeModal,
  }
});

export default useModalStore