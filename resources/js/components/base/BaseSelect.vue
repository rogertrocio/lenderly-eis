<template>
  <label :for="id" class="form-label">
    {{ label }} <span v-if="required" class="text-danger">*</span>
  </label>
  <select
    :id="id"
    v-bind="$attrs"
    @change="$emit('update:modelValue', $event.target.value)"
    :value="modelValue"
    class="form-select"
    :class="{'is-invalid' : error !== null }">
    <option selected value="">{{ optionLabel }}</option>
    <option
      v-for="(option, index) in options"
      :key="index"
      :value="option.value">
      {{ option.label }}
    </option>
  </select>
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
  optionLabel: {
    type: String,
    default: '',
  },
  options: {
    type: Array,
    required: true
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
