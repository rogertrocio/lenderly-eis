<template>
  <section>
    <h1 class="h3">Users</h1>
    <p>Lorem, ipsum dolor sit amet consectetur adipisicing elit. Quos, aut.</p>

    <div class="row mb-3">
      <div class="col d-flex justify-content-end align-items-center gap-3">
        <BaseInput
          label=""
          id="search"
          placeholder="Search"
          style="max-width: 400px;"
          v-model="store.filter.search" />

        <BaseSelect
          label=""
          id="role"
          style="max-width: 300px;"
          option-label="Select Role"
          :options="storeCommon.roles"
          v-model="store.filter.role" />

      <div class="btn-group " role="group" aria-label="Small button group">
        <BaseButton
          type="button"
          label="Export CSV"
          variant="warning"
          @click="exportCsv" />
        <BaseButton
          type="button"
          label="Export PDF"
          variant="secondary"
          @click="exportPdf" />
      </div>
      </div>
    </div>

    <table class="table">
      <thead>
        <tr>
          <th></th>
          <th scope="col">Last Name</th>
          <th scope="col">First Name</th>
          <th scope="col">Email Address</th>
          <th scope="col" class="text-center">Check In</th>
          <th scope="col" class="text-center">Check Out</th>
          <th scope="col" class="text-center">Role</th>
          <th class="text-center">Action</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="user in store.users" :key="user.id">
          <td>
            <img :src="user.avatar !== null ? user.avatar_url : AvatarPlaceholder" alt="User Avatar" class="rounded-circle" style="width: 30px; height: 30px">
          </td>
          <td>{{ user.last_name }}</td>
          <td>{{ user.first_name }}</td>
          <td>{{ user.email }}</td>
          <td class="text-center">{{ user.latest_attendance ? user.latest_attendance.check_in : '-' }}</td>
          <td class="text-center">{{ user.latest_attendance ? user.latest_attendance.check_out : '-' }}</td>
          <td class="text-center">{{ user.roles.length > 0 ? user.roles[0].name : '-'}}</td>
          <td class="text-center">
            <div class="col-12 d-flex justify-content-center">
              <BaseButton
                type="button"
                label="Edit"
                variant="primary"
                style="--bs-btn-padding-y: .25rem; --bs-btn-padding-x: .5rem; --bs-btn-font-size: .75rem;"
                class="me-2 btn-sm"
                @click="router.push(`/users/${user.id}`)"/>

              <BaseButton
                type="button"
                label="Delete"
                variant="danger"
                style="--bs-btn-padding-y: .25rem; --bs-btn-padding-x: .5rem; --bs-btn-font-size: .75rem;"
                class="btn-sm"
                @click="showUserDeleteConfirmDialog(), selectedUser = user" />
            </div>
          </td>
        </tr>
      </tbody>
    </table>

    <div class="d-flex justify-content-end">
			<nav aria-label="pagination">
				<ul class="pagination">
          <template v-for="(link, index) in store.pagination.links" :key="index">
            <!-- Previous Link -->
            <li
              v-if="index === 0"
              class="page-item"
              :class="[link.url !== null ? '' : 'disabled pe-auto']"
              @click="onPageChange(currentPage - 1)">
              <a href="#" class="page-link" v-html="link.label"></a>
            </li>

            <!-- Page Number Link -->
            <li
              v-if="index !== 0 && store.pagination.links.length !== index + 1"
              class="page-item"
              :class="[store.pagination.current_page === index ? 'active' : '']"
              @click="onPageChange(index)">
              <a href="#" class="page-link" v-html="link.label"></a>
            </li>

            <!-- Next Link -->
            <li
              v-if="store.pagination.links.length === index + 1"
              class="page-item"
              :class="[link.url !== null ? '' : 'disabled pe-auto']"
              @click="onPageChange(currentPage + 1)">
              <a href="#" class="page-link" v-html="link.label"></a>
            </li>
          </template>
				</ul>
			</nav>
    </div>
  </section>
  <ConfirmDialog
    ref="userDeleteConfirmDialogRef"
    id="userDeleteModal"
    title="Confirmation"
    :message="`You’re about to delete <strong>${selectedUser.name}</strong> user account. Are you sure?`"
    confirmLabel="Confirm"
    confirmVariant="danger"
    icon="bi-exclamation-triangle"
    iconVariant="danger"
    :loading="store.loading"
    @confirm="deleteUser"
    @cancel="closeUserDeleteConfirmDialog" />
</template>
<script setup>
import { onMounted, watch, ref } from 'vue'
import BaseInput from '../../components/base/BaseInput.vue'
import BaseButton from '../../components/base/BaseButton.vue'
import BaseSelect from '../../components/base/BaseSelect.vue'
import { useUserStore } from '../../stores/user'
import { debounce } from 'lodash'
import ConfirmDialog from '../../components/dialogs/ConfirmDialog.vue'
import { toast } from 'vue3-toastify'
import AvatarPlaceholder from '../../../../public/images/avatar.jpg'
import { useDate } from '../../composables/date'
import { useRouter } from 'vue-router'
import { useCommonStore } from '../../stores/common'

const store = useUserStore()
const router = useRouter()
const storeCommon = useCommonStore()
const date = useDate()
const selectedUser = ref({})
const currentPage = ref(1)
const userDeleteConfirmDialogRef = ref(null)

watch(() => store.filter.search, debounce(() => {
  currentPage.value = 1
  store.getUsers()
}, 500))

watch(() => store.filter.role, () => {
  currentPage.value = 1
  store.getUsers()
})

onMounted(async () => {
  store.getUsers()
  storeCommon.getRoles()
})

const showUserDeleteConfirmDialog = () => { userDeleteConfirmDialogRef.value.show() }

const closeUserDeleteConfirmDialog = () => { userDeleteConfirmDialogRef.value.hide() }

const deleteUser = async () => {
  try {
    await store.deleteUser(selectedUser.value.id)

    toast.success(`${selectedUser.value.name} user account deleted successfully.`)

    selectedUser.value = {}

    currentPage.value = (store.users.length === 1 && currentPage.value !== 1) ? currentPage.value - 1 : currentPage.value

    store.getUsers(currentPage.value)

    closeUserDeleteConfirmDialog()
  } catch (e) {
    toast.error(`${store.errorMessage}`)
  }
}

const onPageChange = (e) => {
  /**
   * Prevent previous button clicke if current page is less than 1
   * Prevent next button clicked if current page is greater than
   * total link page minus previous and next link
   **/
  if ((e < 1) || (e > store.pagination.links.length - 2)) return false

  currentPage.value = e
  store.getUsers(currentPage.value)
}

const exportCsv = async () => {
  try {
    await store.exportReport('csv')
  } catch (e) {
    toast.error(`${store.errorMessage}`)
  }
}

const exportPdf = async () => {
  try {
    await store.exportReport('pdf')
  } catch (e) {
    toast.error(`${store.errorMessage}`)
  }
}
</script>
