<template>
   <AppLayout>
        <div class="space-y-6 p-6">
             <Head title="Appointment Details" />

    <div class="max-w-3xl">
        <div class="bg-transparent space-y-6">

            <!-- Header -->
            <div class="flex justify-between items-start">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-300">
                        {{ appointment.title }}
                    </h1>
                    <p class="text-sm text-gray-500 mt-1">
                        Created by: {{ appointment.user?.name }}
                    </p>
                </div>

                <span
                    :class="[
                        'px-3 py-1 text-xs font-semibold rounded-full',
                        statusColors[appointment.status]
                    ]"
                >
                    {{ appointment.status }}
                </span>
            </div>

            <!-- Description -->
            <div>
                <h2 class="text-sm font-semibold text-gray-600 dark:text-gray-200 mb-1">
                    Description
                </h2>
                <p class="text-gray-700 dark:text-gray-300 whitespace-pre-line">
                    {{ appointment.description }}
                </p>
            </div>

            <!-- Time Information -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h2 class="text-sm font-semibold text-gray-600 dark:text-gray-200 mb-1">
                        Start Time
                    </h2>
                    <p class="text-gray-800 dark:text-gray-300">
                        {{ formatDate(appointment.start_time) }}
                    </p>
                </div>

                <div>
                    <h2 class="text-sm font-semibold text-gray-600 dark:text-gray-200 mb-1">
                        End Time
                    </h2>
                    <p class="text-gray-800 dark:text-gray-300">
                        {{ formatDate(appointment.end_time) }}
                    </p>
                </div>
            </div>

            <!-- Location -->
            <div v-if="appointment.location">
                <h2 class="text-sm font-semibold text-gray-600 dark:text-gray-200 mb-1">
                    Location
                </h2>
                <p class="text-gray-800 dark:text-gray-300">
                    {{ appointment.location }}
                </p>
            </div>

            <!-- Back Button -->
            <div class="pt-4">
                <Link
                    href="/appointments"
                    class="inline-block px-4 py-2 bg-gray-900 text-white rounded-lg hover:bg-gray-700 transition"
                >
                    Back to Appointments
                </Link>
            </div>

        </div>
    </div>
        </div>
   </AppLayout>
</template>

<script setup>
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link } from '@inertiajs/vue3'

const props = defineProps({
    appointment: Object
})

const formatDate = (date) => {
    return new Date(date).toLocaleString()
}

const statusColors = {
  pending: 'bg-yellow-100 text-yellow-800',
  confirmed: 'bg-blue-100 text-blue-800',
  cancelled: 'bg-red-100 text-red-800',
  completed: 'bg-green-100 text-green-800',
}


</script>