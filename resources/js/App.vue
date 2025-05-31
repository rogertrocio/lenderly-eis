<template>
  <div>{{ message }}</div>

  <!-- Button trigger modal -->
  <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
      Launch demo modal
  </button>
</template>
<script setup>
import { ref } from 'vue';

const message = ref('Hello world');
</script>
