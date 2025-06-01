<template>
  <!-- Header page -->
  <MainHeader />

  <!-- Sidebar and Content page-->
  <div class="container-fluid">
    <div class="row">
      <MainSidebar />

      <main class="col-md-10 ms-sm-auto px-4 py-4">
        <RouterView />
      </main>
    </div>
  </div>
</template>
<script setup>
import { RouterView } from 'vue-router';
import MainHeader from './partials/MainHeader.vue';
import MainSidebar from './partials/MainSidebar.vue';
</script>
