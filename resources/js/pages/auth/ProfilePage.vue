<template>
  <section>
    <h1 class="h3">Profile</h1>
    <p>Lorem ipsum dolor sit amet consectetur, adipisicing elit. Alias, rem.</p>

    <div class="row py-3">
      <div class="col-3">
        <div class="fs-6 fw-medium">Basic information</div>
        <span class="fw-light" style="font-size: 14px;">Lorem ipsum dolor sit amet consectetur adipisicing elit. Labore, reprehenderit.</span>
      </div>
      <div class="col-9">
        <form class="row g-3" @submit.prevent="update">
          <div class="col-md-6">
            <BaseInput
              label="First Name"
              id="first_name"
              placeholder="First Name"
              v-model="model.first_name"
              :required="true" />
          </div>
          <div class="col-md-6">
            <BaseInput
              label="Last Name"
              id="last_name"
              placeholder="Last Name"
              v-model="model.last_name"
              :required="true" />
          </div>

          <div class="col-md-6">
            <BaseInput
              label="Email Address"
              id="email"
              placeholder="Email Address"
              v-model="model.email"
              :required="true" />
          </div>

          <div class="col-md-6">
            <BaseInput
              label="Phone Number"
              id="phone"
              placeholder="Phone Number"
              v-model="model.phone" />
          </div>

          <div class="col-md-6">
            <BaseInput
              label="Job Title"
              id="job"
              placeholder="Job Title"
              v-model="model.job" />
          </div>

          <div class="col-12 d-flex justify-content-end">
            <BaseButton
              type="button"
              label="Cancel"
              variant="secondary"
              class="me-2"
              :disabled="store.loading"
              @click="router.push({'name': 'Dashboard'})" />

            <BaseButton
              type="submit"
              label="Update Profile"
              variant="primary"
              :loading="store.loading"
              :disabled="store.loading" />
          </div>
        </form>
      </div>
    </div>
  </section>
</template>
<script setup>
import { onMounted, ref } from 'vue'
import { useRouter } from 'vue-router'
import BaseInput from '../../components/base/BaseInput.vue'
import BaseButton from '../../components/base/BaseButton.vue'
import { toast } from 'vue3-toastify'
import { useProfileStore } from '../../stores/profile'

const router = useRouter()
const store = useProfileStore()
const model = ref({
  first_name: null,
  last_name: null,
  email: null,
  phone: null,
  job: null,
  avatar: null,
})

onMounted(() => {
  profile()
})

const profile = async () => {
  try {
    await store.getProfile()
    model.value = store.profile
  } catch (e) {
    toast.error(store.errorMessage)
  }
}

const update = async () => {
  try {
    await store.updateProfile(model.value)

    toast.success('Your profile successfully updated.')

    profile()

    router.push({'name': 'Profile'})
  } catch (e) {
    toast.error(store.errorMessage)
  }
}
</script>
