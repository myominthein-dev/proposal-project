<template>
  <AppLayout>
    <div class="space-y-6 p-6">
      <div>
        <Link href="/appointments" class="text-sm text-blue-600 hover:text-blue-800">
          ← Back to Appointments
        </Link>
        <Heading as="h1" :title="'Edit Appointment'" class="mt-2 text-3xl">Edit Appointment</Heading>
      </div>

      <div class="max-w-2xl rounded-lg border border-gray-200 bg-white p-6 dark:border-gray-700 dark:bg-black dark:text-gray-100">
        <form @submit.prevent="submitForm" class="space-y-6">
          <!-- Title -->
          <div class="grid gap-2">
            <Label >
              Title <span class="text-red-500">*</span>
            </Label>
            <Input
              v-model="form.title"
              type="text"
              placeholder="Appointment title"
            />
            
            <InputError :message="form.errors.title" />
          </div>

          <!-- Description -->
          <div class="grid gap-2">
            <Label>
              Description
            </Label>
            <textarea
              v-model="form.description"
              rows="4"
              :class="cn('file:text-foreground placeholder:text-muted-foreground selection:bg-primary selection:text-primary-foreground dark:bg-input/30 border-input h-9 w-full min-w-0 rounded-md border bg-transparent px-3 py-1 text-base shadow-xs transition-[color,box-shadow] outline-none file:inline-flex file:h-7 file:border-0 file:bg-transparent file:text-sm file:font-medium disabled:pointer-events-none disabled:cursor-not-allowed disabled:opacity-50 md:text-sm',
              'focus-visible:border-ring focus-visible:ring-ring/50 focus-visible:ring-[3px]',
              'aria-invalid:ring-destructive/20 dark:aria-invalid:ring-destructive/40 aria-invalid:border-destructive')"
              placeholder="Appointment details..."
            ></textarea>
          

            <InputError  :message="form.errors.description "/>
          </div>

          <!-- Date and Time -->
          <div class="grid grid-cols-2 gap-4">
            <div class="grid gap-2">
              <Label >
                Start Time <span class="text-red-500">*</span>
              </Label>
              <Input
                v-model="form.start_time"
                type="datetime-local"
              />
              <p v-if="form.errors.start_time" class="mt-1 text-sm text-red-600">
                {{ form.errors.start_time }}
              </p>
              <InputError :message="form.errors.start_time" />
            </div>

            <div class="grid gap-2">
              <Label>
                End Time <span class="text-red-500">*</span>
              </Label>
              <Input
                v-model="form.end_time"
                type="datetime-local"
              />
              <InputError :class="form.errors.end_time" />
            </div>
          </div>

          <!-- Location -->
          <div class="grid gap-2">
            <Label>
              Location
            </Label>
            <Input
              v-model="form.location"
              type="text"
              
              placeholder="Meeting location or online link"
            />
          
            <InputError :message="form.errors.location" />
          </div>

          <!-- Status (Admin only) -->
          <div class="grid gap-2">
            <Label>
              Status
            </Label>
            <select
              v-model="form.status"
              class="dark:bg-input/30 border-input h-9 w-full rounded-md border bg-transparent px-3 py-1 text-base shadow-xs transition-[color,box-shadow] outline-none"
            >
              <option value="pending">Pending</option>
              <option value="confirmed">Confirmed</option>
              <option value="cancelled">Cancelled</option>
              <option value="completed">Completed</option>
            </select>
            <p v-if="form.errors.status" class="mt-1 text-sm text-red-600">
              {{ form.errors.status }}
            </p>
          </div>

          <!-- Submit Button -->
          <div class="flex gap-3">
            <Button type="submit" :disabled="form.processing">
              {{ form.processing ? 'Saving...' : 'Save Changes' }}
            </Button>
            <Link href="/appointments">
              <Button type="button" variant="outline">Cancel</Button>
            </Link>
          </div>
        </form>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3'
import { Link } from '@inertiajs/vue3'
import AppLayout from '@/layouts/AppLayout.vue'
import Heading from '@/components/Heading.vue'
import { Button } from '@/components/ui/button'
import Label from '@/components/ui/label/Label.vue'
import Input from '@/components/ui/input/Input.vue'
import InputError from '@/components/InputError.vue'
import { cn } from '@/lib/utils'

const props = defineProps({
  appointment: Object,
})

const form = useForm({
  title: props.appointment.title,
  description: props.appointment.description,
  start_time: formatDateTimeLocal(props.appointment.start_time),
  end_time: formatDateTimeLocal(props.appointment.end_time),
  location: props.appointment.location,
  status: props.appointment.status,
})

function formatDateTimeLocal(dateTime) {
  const date = new Date(dateTime)
  const year = date.getFullYear()
  const month = String(date.getMonth() + 1).padStart(2, '0')
  const day = String(date.getDate()).padStart(2, '0')
  const hours = String(date.getHours()).padStart(2, '0')
  const minutes = String(date.getMinutes()).padStart(2, '0')
  return `${year}-${month}-${day}T${hours}:${minutes}`
}

const submitForm = () => {
  form.put(`/appointments/${props.appointment.id}`)
}
</script>
