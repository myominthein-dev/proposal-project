<template>
  <AppLayout>
    <div class="space-y-6 p-6">

      <div class="flex items-center justify-between">
        <div>
          <Heading as="h1" title="Appointments" class="text-3xl">Appointments</Heading>
          <p class="text-sm text-gray-600">Manage your appointments</p>
        </div>
        <Link href="/appointments/create" class="inline-flex">
          <Button class="dark:bg-gray-400">
            <Plus class="mr-2 h-4 w-4" />
            New Appointment
          </Button>
        </Link>
      </div>

      <div class="flex gap-2">
        <input v-model="searchQuery" type="text" placeholder="Search appointments..." @input="handleSearch"
          class="w-full rounded-lg border border-gray-300 px-4 py-2 text-sm" />
      </div>

      <div class="overflow-x-auto rounded-lg border border-gray-400 dark:text-gray-200 dark:bg-gray-800">
        <table class="w-full text-sm">
          <thead class="border-b border-gray-200 dark:bg-gray-800 ">
            <tr>
              <th class="px-6 py-3 text-left font-semibold">Title</th>
              <th class="px-6 py-3 text-left font-semibold">Date & Time</th>
              <th class="px-6 py-3 text-left font-semibold">Location</th>
              <th v-if="isAdmin" class="px-6 py-3 text-left font-semibold">
                User
              </th>
              <th class="px-6 py-3 text-left font-semibold">Status</th>
              <th class="px-6 py-3 text-right font-semibold">Actions</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-200">
            <tr v-for="appointment in displayedAppointments" :key="appointment.id" class="hover:bg-gray-700">
              <td class="px-6 py-4 font-medium text-gray-900 dark:text-gray-200">
                {{ appointment.title }}
              </td>
              <td class="px-6 py-4 text-gray-600 dark:text-gray-200">
                {{ formatDateTime(appointment.start_time) }}
              </td>
              <td class="px-6 py-4 text-gray-600 dark:text-gray-200">
                {{ appointment.location || '—' }}
              </td>
              <td v-if="isAdmin" class="px-6 py-4 text-gray-600 dark:text-gray-200">
                {{ appointment?.user?.name }}
              </td>
              <td class="px-6 py-4">
                <span :class="[
                  'inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium',
                  statusColors[appointment.status],
                ]">
                  {{ appointment.status }}
                </span>
              </td>
              <td class="px-6 py-4 text-right">
                <div class="flex justify-end gap-2">
                  <Button v-if="isAdmin" @click="open(appointment)" class="dark:bg-transparent" variant="outline" size="sm">
                    <Send class="h-4 w-4 dark:text-blue-500" />
                  </Button>
                  <Link :href="`/appointments/${appointment.id}/edit`">
                    <Button variant="outline" size="sm">
                      <Edit class="h-4 w-4 dark:text-white" />
                    </Button>
                  </Link>
                  <Button size="sm" @click="deleteAppointment(appointment.id)"
                    class="bg-red-600 dark:border border-red-600 dark:bg-transparent">
                    <Trash2 class="h-4 w-4 dark:text-red-600" />
                  </Button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>

        <div v-if="displayedAppointments.length === 0"
          class="flex flex-col dark:bg-gray-300 items-center justify-center py-12 text-center">
          <Calendar class="mb-4 h-12 w-12 text-gray-400" />
          <p class="text-lg font-medium text-gray-900">No appointments</p>
          <p class="text-sm text-gray-600">
            Create your first appointment to get started.
          </p>
        </div>
      </div>


      <!-- Modal -->
      <transition name="fade">
        <div v-if="isOpen" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm"
          @click.self="close">
          <div class="bg-white dark:bg-gray-200 w-full max-w-lg rounded-2xl shadow-xl p-6 relative">
            <!-- Close -->
            <button @click="close" class="absolute top-4 dark:text-gray-700 right-4 text-gray-400 hover:text-gray-600">
              ✕
            </button>

            <h2 class="text-xl dark:text-gray-700 font-semibold mb-4">Send Email</h2>

            <form @submit.prevent="submitMail" class="space-y-4">

              <!-- Subject -->
              <div>
                <label class="block text-sm dark:text-gray-700 font-medium mb-1">Subject</label>
                <input v-model="formMail.subject" type="text" required
                  class="w-full border dark:text-gray-700 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none" />
              </div>

              <!-- Message -->
              <div>
                <label class="block text-sm dark:text-gray-700 font-medium mb-1">Message</label>
                <textarea v-model="formMail.message" rows="4" required
                  class="w-full dark:text-gray-700 border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none resize-none" />
              </div>

              <!-- Actions -->
              <div class="flex justify-end gap-3 pt-2">
                <Button type="button" @click="close" size="sm" class="bg-black hover:bg-slate-900 text-white">
                  Cancel
                </Button>

                <Button :disabled="formMail.processing" type="submit" size="sm"
                  class="bg-blue-600 hover:bg-blue-700 text-white">
                  {{ formMail.processing ? 'Sendng...' : 'Send' }}
                </Button>
              </div>
            </form>
          </div>
        </div>
      </transition>
    </div>
  </AppLayout>
</template>

<script setup>
import Heading from '@/components/Heading.vue';
import Button from '@/components/ui/button/Button.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { Calendar, Edit, Mail, Plus, Send, Trash2 } from 'lucide-vue-next';
import { computed, ref, onMounted, onBeforeUnmount, reactive } from 'vue';
import { Link, useForm } from '@inertiajs/vue3';
const props = defineProps({
  appointments: Array,
  isAdmin: Boolean
})

const searchQuery = ref('')
const appointments = ref([...props.appointments])
const filteredAppointments = ref([])

const statusColors = {
  pending: 'bg-yellow-100 text-yellow-800',
  confirmed: 'bg-blue-100 text-blue-800',
  cancelled: 'bg-red-100 text-red-800',
  completed: 'bg-green-100 text-green-800',
}

const displayedAppointments = computed(() =>
  filteredAppointments.value.length > 0
    ? filteredAppointments.value
    : appointments.value,
)

const handleSearch = async (e) => {
  const query = e.target.value
  if (query.length > 0) {
    filteredAppointments.value = appointments.value.filter((apt) =>
      apt.title.toLowerCase().includes(query.toLowerCase()) ||
      apt.description?.toLowerCase().includes(query.toLowerCase()) ||
      apt.location?.toLowerCase().includes(query.toLowerCase()),
    )
  } else {
    filteredAppointments.value = []
  }
}

const deleteAppointment = (id) => {
  if (!confirm('Are you sure you want to delete this appointment?')) return

  const form = useForm({})
  form.delete(`/appointments/${id}`, {
    onSuccess: () => {
      const idx = appointments.value.findIndex((a) => a.id === id)
      if (idx !== -1) appointments.value.splice(idx, 1)

      const fIdx = filteredAppointments.value.findIndex((a) => a.id === id)
      if (fIdx !== -1) filteredAppointments.value.splice(fIdx, 1)
    },
  })
}

const formatDateTime = (dateTime) => {
  return new Date(dateTime).toLocaleString('en-US', {
    year: 'numeric',
    month: 'short',
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit',
  })
}

// mail logic

const isOpen = ref(false)

const formMail = useForm({
  to: "",
  subject: "",
  message: "",
})

const open = (param) => {
  formMail.to = param.user.email;
  isOpen.value = true
}

const close = () => {
  isOpen.value = false
}

const submitMail = () => {
  formMail.post('/mail/send', {
    onSuccess: () => {
      close()
    }
  })

}

// ESC key close
const handleEsc = (e) => {
  if (e.key === "Escape") close()
}

onMounted(() => {
  window.addEventListener("keydown", handleEsc)
})

onBeforeUnmount(() => {
  window.removeEventListener("keydown", handleEsc)
})
</script>

<style scoped></style>