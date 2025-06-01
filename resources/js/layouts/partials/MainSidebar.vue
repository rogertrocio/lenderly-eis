<template>
  <aside class="col-md-2 sidebar d-none d-md-block">
    <div class="d-flex flex-column flex-shrink-0 p-3 bg-light" style="height: 100%;">
      <div class="d-flex flex-column align-items-center">
        <img :src="storeProfile.profile.avatar !== null ? storeProfile.profile.avatar_url : AvatarPlaceholder" alt="User Avatar" class="rounded-circle mb-3" style="width: 80px; height: 80px">
        <div class="d-flex flex-column">
          <h5 class="mb-0 text-center">{{ storeProfile.profile.name }}</h5>
          <small class="text-muted text-center">{{ storeProfile.profile.email }}</small>

          <div class="my-2 w-100 text-center">
            <BaseButton
              v-if="!storeProfile.profile.is_already_checked_in"
              type="button"
              class="my-2"
              label="Check-In"
              variant="warning"
              style="--bs-btn-padding-y: .25rem; --bs-btn-padding-x: .5rem; --bs-btn-font-size: .75rem;"
              @click="showCheckInConfirmDialog" />

            <BaseButton
              v-if="storeProfile.profile.is_already_checked_in"
              type="button"
              class="my-2"
              label="Check-Out"
              variant="warning"
              style="--bs-btn-padding-y: .25rem; --bs-btn-padding-x: .5rem; --bs-btn-font-size: .75rem;"
              @click="showCheckOutConfirmDialog"/>
          </div>

          <div class="d-flex flex-column" style="font-size: 12px;">
            <div class="flex-fill"><span class="fw-medium">Check-In:</span> {{ storeProfile.profile.latest_attendance?.check_in ?? ' No data' }}</div>
            <div class="flex-fill"><span class="fw-medium">Check-Out:</span> {{ storeProfile.profile.latest_attendance?.check_out ?? ' No data' }}</div>
          </div>
        </div>
      </div>
      <hr>
      <ul class="nav nav-pills flex-column mb-auto">
        <li>
          <RouterLink
            to="/"
            exactActiveClass="active"
            class="nav-link d-flex align-items-center gap-3"
            :class="{' link-dark': route.path !== '/'}">
            <i class="bi bi-columns-gap"></i>
            <span>Dashboard</span>
          </RouterLink>
        </li>
        <li>
          <RouterLink
            to="/employees"
            exactActiveClass="active"
            class="nav-link d-flex align-items-center gap-3"
            :class="{' link-dark': route.path !== '/employees'}">
            <i class="bi bi-people-fill"></i>
            <span>Employees</span>
          </RouterLink>
        </li>
      </ul>
    </div>
  </aside>

  <ConfirmDialog
    ref="checkInConfirmDialogRef"
    id="sampleModal"
    title="Confirmation"
    message="You’re about to <strong>Check-In</strong> for today. Are you sure?"
    confirmLabel="Check-In"
    confirmVariant="warning"
    icon="bi-clock"
    :loading="storeAuth.loading"
    @confirm="checkIn"
    @cancel="closeCheckInConfirmDialog" />

  <ConfirmDialog
    ref="checkOutConfirmDialogRef"
    id="sampleModal"
    title="Confirmation"
    message="You’re about to <strong>Check-Out</strong> for today. Are you sure?"
    confirmLabel="Check-Out"
    confirmVariant="warning"
    icon="bi-clock"
    :loading="storeAuth.loading"
    @confirm="checkOut"
    @cancel="closeCheckOutConfirmDialog" />
</template>
<script setup>
import { onMounted, ref } from 'vue'
import { useProfileStore } from '../../stores/profile'
import { useAuthStore } from '../../stores/auth'
import AvatarPlaceholder from '../../../../public/images/avatar.jpg'
import { useRoute } from 'vue-router'
import BaseButton from '../../components/base/BaseButton.vue'
import { toast } from 'vue3-toastify'
import ConfirmDialog from '../../components/dialogs/ConfirmDialog.vue'
import { useDate } from '../../composables/date'

const route = useRoute()
const storeProfile = useProfileStore()
const storeAuth = useAuthStore()
const date = useDate()
const checkInConfirmDialogRef = ref(null)
const checkOutConfirmDialogRef = ref(null)

onMounted(() => {
  profile()
})

const profile = async () => {
  try {
    await storeProfile.getProfile()
  } catch (e) {
    toast.error(storeProfile.errorMessage)
  }
}

const showCheckInConfirmDialog = () => { checkInConfirmDialogRef.value.show() }

const closeCheckInConfirmDialog = () => { checkInConfirmDialogRef.value.hide() }

const checkIn = async () => {
  try {
    const response = await storeAuth.checkIn()
    toast.success(`You've successfully checked in today, ${date.format(response.data.data.check_in, 'MMM DD, YYYY [at] HH:mm:ss')}`)

    closeCheckInConfirmDialog()
    profile()
  } catch (e) {
    toast.error(storeAuth.errorMessage)
  }
}

const showCheckOutConfirmDialog = () => { checkOutConfirmDialogRef.value.show() }

const closeCheckOutConfirmDialog = () => { checkOutConfirmDialogRef.value.hide() }

const checkOut = async () => {
  try {
    const response = await storeAuth.checkOut()
    toast.success(`You've successfully checked out today, ${date.format(response.data.data.check_out, 'MMM DD, YYYY [at] HH:mm:ss')}`)

    closeCheckOutConfirmDialog()
    profile()
  } catch (e) {
    toast.error(storeAuth.errorMessage)
  }
}
</script>
