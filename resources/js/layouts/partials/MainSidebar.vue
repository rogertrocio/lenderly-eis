<template>
  <aside class="col-md-2 sidebar d-none d-md-block">
    <div class="d-flex flex-column flex-shrink-0 p-3 bg-light" style="height: 100%;">
      <div class="d-flex flex-column align-items-center">
        <img :src="store.profile.avatar !== null ? store.profile.avatar_url : AvatarPlaceholder" alt="User Avatar" class="rounded-circle mb-3" style="width: 80px; height: 80px">
        <div class="text-center">
          <h5 class="mb-0">{{ store.profile.name }}</h5>
          <small class="text-muted">{{ store.profile.email }}</small>
        </div>
      </div>
      <hr>
      <ul class="nav nav-pills flex-column mb-auto">
        <li>
          <RouterLink to="/" class="nav-link active d-flex align-items-center gap-3">
            <i class="bi bi-columns-gap"></i>
            <span>Dashboard</span>
          </RouterLink>
        </li>
        <li>
          <RouterLink to="/employees" class="nav-link link-dark d-flex align-items-center gap-3">
            <i class="bi bi-people-fill"></i>
            <span>Employees</span>
          </RouterLink>
        </li>
      </ul>
    </div>
  </aside>
</template>
<script setup>
import { onMounted } from 'vue'
import { useProfileStore } from '../../stores/profile'
import AvatarPlaceholder from '../../../../public/images/avatar.jpg'

const store = useProfileStore()

onMounted(async () => {
  try {
    await store.getProfile()
  } catch (e) {
    toast.error(store.errorMessage)
  }
})
</script>
