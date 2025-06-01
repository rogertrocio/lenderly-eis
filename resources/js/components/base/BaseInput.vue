<template>
  <label :for="id" class="form-label">
    {{ label }} <span v-if="required" class="text-danger">*</span>
  </label>
  <input
    type="text"
    :id="id"
    v-bind="$attrs"
    @input="$emit('update:modelValue', $event.target.value)"
    :value="modelValue"
    class="form-control"
    :class="{'is-invalid' : error !== null }" />
    <div v-if="error !== null" class="invalid-feedback">
      {{ error }}
    </div>
</template>
<script setup>
defineProps({
  id: {
    type: [String, Boolean],
    default: false,
  },
  label: {
    type: [String, Boolean],
    default: false,
  },
  modelValue: {
    type: String,
    default: '',
  },
  required: {
    type: Boolean,
    default: false,
  },
  error: {
    type: String,
    default: null,
  },
})

defineEmits(['update:modelValue'])
</script>
