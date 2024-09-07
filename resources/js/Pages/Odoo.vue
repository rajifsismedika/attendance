<script setup lang="ts">
defineProps<{
    data?: any;
}>();
</script>
<template>
    <div>
        <table>
            <template v-for="row in data">
                <tr v-if="row.task != 'None' && !row.task?.toLowerCase().includes('meet')">
                    <td>{{ row.tanggal }}</td>
                    <td>{{ row.employee }}</td>
                    <td>{{ row.project }}</td>
                    <td>Task</td>
                    <td>{{ row.task }}</td>
                </tr>
            </template>
        </table>
    </div>
</template>
