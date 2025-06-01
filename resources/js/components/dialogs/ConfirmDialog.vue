<template>
  <!-- Modal -->
  <div class="modal fade" :id="id" ref="modalRef" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" >
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="staticBackdropLabel">{{ title }}</h1>
          <button type="button" class="btn-close" @click="hide" aria-label="Close"></button>
        </div>
        <div class="modal-body d-flex flex-column justify-content-center align-items-center">
          <i
            class="bi"
            :class="[
              icon !== null ? icon : '',
              iconVariant !== null ? `text-${iconVariant}` : ''
            ]"
            style="font-size: 50px;"></i>
          <span class="text-center" v-html="message"></span>
        </div>
        <div class="modal-footer">
          <BaseButton
            type="button"
            :label="cancelLabel"
            :variant="cancelVariant"
            :disabled="loading"
            @click="emit('cancel')" />

          <BaseButton
            type="button"
            :label="confirmLabel"
            :variant="confirmVariant"
            :loading="loading"
            :disabled="loading"
            @click="emit('confirm')" />
        </div>
      </div>
    </div>
  </div>
</template>
<script setup>
import { onMounted, ref } from 'vue'
import { Modal } from 'bootstrap'
import BaseButton from '../base/BaseButton.vue'

defineProps({
  id: {
    type: [String, Number],
    required: true,
  },
  title: {
    type: String,
    required: true,
  },
  message: {
    type: String,
    default: 'This is message.',
    required: false,
  },
  loading: {
    type: Boolean,
    default: false,
  },
  confirmLabel: {
    type: String,
    required: false,
    default: 'Confirm',
  },
  confirmVariant: {
    type: String,
    required: false,
    default: 'primary',
  },
  cancelLabel: {
    type: String,
    required: false,
    default: 'Cancel',
  },
  cancelVariant: {
    type: String,
    required: false,
    default: 'secondary'
  },
  icon: {
    type: String,
    required: false,
    default: 'bi-exclamation-circle'
  },
  iconVariant: {
    type: String,
    required: false,
    default: 'secondary'
  }
})

const emit = defineEmits(['confirm', 'cancel'])

let modal = null
const modalRef = ref(null)

onMounted(() => {
 modal = new Modal(modalRef.value)
})

const show = () => { modal.show() }

const hide = () => { modal.hide() }

defineExpose({ show, hide })
</script>
