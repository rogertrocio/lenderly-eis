<template>
  <button
    type="submit"
    v-bind="$attrs"
    :disabled="disabled || loading"
    class="btn"
    :class="[variant !== null ? `btn-${variant}` : '']">
    <span v-if="loading" class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
    {{ label }}
  </button>
</template>
<script setup>
defineProps({
  label: {
    type: String,
    default: 'Button'
  },
  variant: {
    type: String,
    default: 'primary',
  },
  loading: {
    type: Boolean,
    default: false,
  },
  disabled: {
    type: Boolean,
    default: false,
  }
})
</script>
