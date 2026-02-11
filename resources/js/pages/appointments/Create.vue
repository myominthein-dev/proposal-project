<template>
    <AppLayout>
        <div class="space-y-6 p-6">
            <div>
        <Link href="/appointments" class="text-sm text-blue-600 hover:text-blue-800">
          ← Back to Appointments
        </Link>
        <Heading as="h1" :title="'Create Appointment'" class="mt-2 text-3xl">Create Appointment</Heading>
      </div>

      <div class="max-w-2xl rounded-lg border border-gray-200 bg-white p-6 dark:border-gray-700 dark:bg-black dark:text-gray-100">
        <form @submit.prevent="submitForm" class="space-y-6">
          <!-- Title -->
          <div>
            <label class="block text-sm font-medium text-gray-900 dark:text-gray-100">
              Title <span class="text-red-500">*</span>
            </label>
            <input
              v-model="form.title"
              type="text"
              class="mt-2 w-full rounded-lg border border-gray-300 px-4 py-2 text-sm focus:border-blue-500 focus:outline-none dark:bg-slate-900 dark:border-gray-600 dark:text-gray-100"
              placeholder="Appointment title"
            />
            <p v-if="form.errors.title" class="mt-1 text-sm text-red-600">
              {{ form.errors.title }}
            </p>
          </div>

          <!-- Description -->
          <div>
            <label class="block text-sm font-medium text-gray-900 dark:text-gray-100">
              Description
            </label>
            <textarea
              v-model="form.description"
              rows="4"
              class="mt-2 w-full rounded-lg border border-gray-300 px-4 py-2 text-sm focus:border-blue-500 focus:outline-none dark:bg-slate-900 dark:border-gray-600 dark:text-gray-100"
              placeholder="Appointment details..."
            ></textarea>
            <p v-if="form.errors.description" class="mt-1 text-sm text-red-600">
              {{ form.errors.description }}
            </p>
          </div>

          <!-- Date and Time -->
          <div class="grid grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-medium text-gray-900 dark:text-gray-100">
                Start Time <span class="text-red-500">*</span>
              </label>
              <input
                v-model="form.start_time"
                type="datetime-local"
                class="mt-2 w-full rounded-lg border border-gray-300 px-4 py-2 text-sm focus:border-blue-500 focus:outline-none dark:bg-slate-900 dark:border-gray-600 dark:text-gray-100"
              />
              <p v-if="form.errors.start_time" class="mt-1 text-sm text-red-600">
                {{ form.errors.start_time }}
              </p>
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-900 dark:text-gray-100">
                End Time <span class="text-red-500">*</span>
              </label>
              <input
                v-model="form.end_time"
                type="datetime-local"
                class="mt-2 w-full rounded-lg border border-gray-300 px-4 py-2 text-sm focus:border-blue-500 focus:outline-none dark:bg-slate-900 dark:border-gray-600 dark:text-gray-100"
              />
              <p v-if="form.errors.end_time" class="mt-1 text-sm text-red-600">
                {{ form.errors.end_time }}
              </p>
            </div>
          </div>

          <!-- Location -->
          <div>
            <label class="block text-sm font-medium text-gray-900 dark:text-gray-100">
              Location
            </label>
            <input
              v-model="form.location"
              type="text"
              class="mt-2 w-full rounded-lg border border-gray-300 px-4 py-2 text-sm focus:border-blue-500 focus:outline-none dark:bg-slate-900 dark:border-gray-600 dark:text-gray-100"
              placeholder="Meeting location or online link"
            />
            <p v-if="form.errors.location" class="mt-1 text-sm text-red-600">
              {{ form.errors.location }}
            </p>
          </div>

          <!-- Submit Button -->
          <div class="flex gap-3">
            <Button type="submit" :disabled="form.processing">
              {{ form.processing ? 'Creating...' : 'Create Appointment' }}
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

const form = useForm({
  title: '',
  description: '',
  start_time: '',
  end_time: '',
  location: '',
})

const submitForm = () => {
  form.post('/appointments')
}
</script>