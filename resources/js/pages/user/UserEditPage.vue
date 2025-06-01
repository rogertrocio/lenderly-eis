<template>
  <section>
    <h1 class="h3">Edit User - {{ store.user.name }}</h1>
    <p>Lorem, ipsum dolor sit amet consectetur adipisicing elit. Quos, aut.</p>

    <!-- Profile Picture section -->
    <div class="row py-3">
      <div class="col-3">
        <div class="fs-6 fw-medium">Profile Picture</div>
        <span class="fw-light" style="font-size: 14px;">Lorem ipsum dolor sit amet consectetur adipisicing elit. Soluta, accusantium.</span>
      </div>
      <div class="col-9 px-5">
        <div class="row g-3">
          <div class="col-md-12 mb-2">
              <img :src="displayedAvatar" class="img-fluid rounded-circle" alt="Profile Picture" style="width: 150px; height: 150px; object-fit: cover;" />
          </div>

          <div class="col-md-6">
            <input
              type="file"
              accept="image/*"
              @change="(e) => uploadImage(e)" />
          </div>
        </div>
      </div>
    </div>

    <!-- Basic Information section -->
    <div class="row py-3">
      <div class="col-3">
        <div class="fs-6 fw-medium">Basic Information</div>
        <span class="fw-light" style="font-size: 14px;">Lorem ipsum dolor sit amet consectetur adipisicing elit. Labore, reprehenderit.</span>
      </div>
      <div class="col-9 px-5">
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
              @click="router.push('/users')" />

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
import { computed, onMounted, ref } from 'vue'
import BaseInput from '../../components/base/BaseInput.vue'
import BaseButton from '../../components/base/BaseButton.vue'
import AvatarPlaceholder from '../../../../public/images/avatar.jpg'
import { useUserStore } from '../../stores/user'
import { useRoute, useRouter } from 'vue-router'
import { toast } from 'vue3-toastify'

const store = useUserStore()
const route = useRoute()
const router = useRouter()
const model = ref({
  first_name: null,
  last_name: null,
  email: null,
  phone: null,
  job: null,
  avatar: null,
})
const tempAvatar = ref(null)
const imageExtensions = ref([
  'image/jpeg',
  'image/png',
  'image/gif',
  'image/webp',
  'image/apng',
  'image/avif',
])

const displayedAvatar = computed(() => {
  if (tempAvatar.value !== null) {
    return renderImage(tempAvatar.value)
  }
  if (tempAvatar.value === null && model.value.avatar !== null && model.value.avatar_url !== null) {
    return model.value.avatar_url
  }
  if (tempAvatar.value === null && model.value.avatar === null && model.value.avatar_url === null) {
    return AvatarPlaceholder
  }
})

onMounted(() => {
  user()
})

const user = async () => {
  try {
    await store.getUser(route.params.id)
    model.value = store.user
  } catch (e) {
    toast.error(store.errorMessage)
  }
}

const update = async () => {
  try {
    if (tempAvatar.value !== null) {
      model.value.avatar = tempAvatar.value
    }
    if (tempAvatar.value === null && model.value.avatar !== null && model.value.avatar_url !== null) {
        model.value.avatar = ''
    }
    if (tempAvatar.value === null && model.value.avatar === null && model.value.avatar_url === null) {
        model.value.avatar = ''
    }

    await store.updateUser(model.value, route.params.id)

    toast.success('User information successfully updated.')

    router.go({'name': 'User'})
  } catch (e) {
    toast.error(store.errorMessage)
  }
}

const uploadImage = (e) => {
  var files = e.target.files || e.dataTransfer.files
  if (files.length === 0) return

  for (let i = 0; i < files.length; i++) {
    if (!isImageFile(files[i])) {
      toast.error(`${files[i].name} must be an image.`)
      continue
    }

    if (!checkFileSize(files[i])) {
      toast.error(`${files[i].name} size must not be greater than 2mb.`)
      continue
    }

    tempAvatar.value = files[i]
  }
}

const renderImage = (file) => {
  const src = URL.createObjectURL(file)
  return src
}

const isImageFile = (file) => {
  return imageExtensions.value.includes(file.type)
}

const checkFileSize = (file) => {
  const mb = (file.size / Math.pow(1024, 2)).toFixed(1) * 1
  return mb <= 2
}
</script>
