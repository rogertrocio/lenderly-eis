<template>
    <div class="vh-100 d-flex justify-content-center align-items-center">
      <div class="d-flex flex-column align-items-center text-center">
        <p>Unathorized</p>
        <img :src="ForbiddenImage" alt="" style="width: 400px; height: 400px;">
        <RouterLink to="/">
          <BaseButton
          type="button"
          label="Back to home page"
          variant="secondary" />
        </RouterLink>
      </div>
    </div>
  </template>
  <script setup>
  import { RouterLink } from 'vue-router'
  import ForbiddenImage from '../../../public/images/403-forbidden.png'
  import BaseButton from '../components/base/BaseButton.vue'
  </script>
