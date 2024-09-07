<script setup lang="ts">
import PrimaryButton from '@/Components/PrimaryButton.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import axios from 'axios';
import { ref, onMounted } from 'vue';
import DigitalClock from '@/Components/DigitalClock.vue'
import { format } from 'date-fns';
import { Capacitor } from '@capacitor/core';
import { notify } from "@kyvg/vue3-notification";

interface Attendance {
  id: number;
  date: string;
  check_in_time: string|undefined;
  check_out_time: string|undefined;
}

const props = defineProps<{
    attendance?: any;
}>();

const attendance = ref<Attendance | undefined>(props.attendance);
const attendanceHistory = ref<Attendance[]>([]);

const checkIn = async () => {
    const res = await axios.post('/check-in')

    const attendanceRes = res.data.attendance

    attendance.value = attendanceRes
    attendanceHistory.value = attendanceHistory.value.map((att) => {
        return att.date == attendance.value?.date ? attendance.value : att
    })
}

const checkOut = async () => {
    const res = await axios.post('/check-out')

    const attendanceRes = res.data.attendance

    attendance.value = attendanceRes
    attendanceHistory.value = attendanceHistory.value.map((att) => {
        return att.date == attendance.value?.date ? attendance.value : att
    })
}

const getHistory = async () => {
    const res = await axios.get('/attendances')

    const attendanceRes = res.data

    attendanceHistory.value = attendanceRes
}

onMounted(() => {
    getHistory()
    notify({
        title: "Authorization",
        text: "You have been logged in!",
        type: 'success',
    });
})

const formatAttTime = (time: string | undefined): string => {
  if (!time) return '-';
  const date = new Date(time);
  return format(date, 'HH:mm');
};

</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <!-- <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Dashboard</h2>
        </template> -->

        <div class="py-0">

            <div class="max-w-md mx-auto">
                <DigitalClock />
            </div>

            <div class="p-4">
                <div class="max-w-md mx-auto bg-white dark:bg-gray-800  shadow-sm rounded-lg p-4">
                    <!-- <div class="text-gray-800 dark:text-gray-200 text-lg font-bold">Attendance Today</div> -->

                    <div class="text-gray-800 dark:text-gray-200">
                        <table class="w-full divide-y divide-gray-200 dark:divide-gray-700 border border-gray-200 dark:border-gray-700 mb-4">
                            <tr>
                                <td class="text-center">Masuk</td>
                                <td class="text-center">Pulang</td>
                            </tr>
                            <tr>
                                <td class="text-center text-2xl">{{ formatAttTime(attendance?.check_in_time) }}</td>
                                <td class="text-center text-2xl">{{ formatAttTime(attendance?.check_out_time) }}</td>
                            </tr>
                        </table>


                        <div v-if="!attendance">
                            <div class="text-center mb-2 font-bold">Buruan Check In !!!</div>
                            <button @click="checkIn" class="w-full p-2 rounded-lg border-white bg-gray-200 text-gray-800 dark:bg-gray-200 dark:text-gray-800">Check In</button>
                        </div>

                        <div v-else-if="!attendance.check_out_time">
                            <button @click="checkOut" class="w-full p-2 rounded-lg border border-white bg-gray-800 text-gray-200 dark:bg-gray-800 dark:text-gray-200">Check Out</button>
                        </div>

                        <div v-else>
                            <div class="font-bold">Dah sana pulang ! Lanjut Besok Lagi.</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="p-4">
                <div class="max-w-md mx-auto bg-white dark:bg-gray-800  shadow-sm rounded-lg p-4">
                    <div class="text-gray-800 dark:text-gray-200 text-lg font-bold">Attendance History</div>

                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <tr v-for="attendance in attendanceHistory" :key="attendance.date">
                            <td class="py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">{{ attendance.date ? format(attendance.date, 'dd/MM/yyyy') : '-' }}</td>
                            <td class="py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300 text-center">{{ formatAttTime(attendance.check_in_time) }}</td>
                            <td class="py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300 text-center">{{ formatAttTime(attendance.check_out_time) }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
