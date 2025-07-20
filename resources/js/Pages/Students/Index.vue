<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref, watch } from 'vue';

const props = defineProps({
    courses: Array,
    selectedCourse: Object,
    selectedId: Number,
    searchEmail: String,
    searchedCourses: Array,
});

const selected = ref(props.selectedId);

watch(selected, (val) => {
    window.location = route('students.index', { course_id: val });
});

const form = useForm({
    identifier: '',
});

function addStudent() {
    if (!selected.value) return;
    form.post(route('students.store', selected.value), {
        preserveScroll: true,
        onSuccess: () => form.reset(),
    });
}
</script>

<template>
    <Head title="Students" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">Students</h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="mb-6 overflow-hidden bg-white p-6 shadow-sm sm:rounded-lg">
                    <label class="mr-2">Select Course:</label>
                    <select v-model="selected" class="rounded border-gray-300">
                        <option v-for="c in courses" :key="c.id" :value="c.id">{{ c.title }}</option>
                    </select>
                </div>

                <div v-if="selectedCourse" class="mb-6 overflow-hidden bg-white p-6 shadow-sm sm:rounded-lg">
                    <form @submit.prevent="addStudent" class="mb-4 flex space-x-2">
                        <input v-model="form.identifier" class="rounded border-gray-300" placeholder="ID or Email" />
                        <button type="submit" class="rounded bg-blue-600 px-3 py-1 text-white">Add</button>
                    </form>
                    <table class="w-full table-auto text-left">
                        <thead>
                            <tr>
                                <th class="border px-2 py-1">ID</th>
                                <th class="border px-2 py-1">Name</th>
                                <th class="border px-2 py-1">Email</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="s in selectedCourse.students" :key="s.id">
                                <td class="border px-2 py-1">{{ s.id }}</td>
                                <td class="border px-2 py-1">{{ s.name }}</td>
                                <td class="border px-2 py-1">{{ s.email }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="overflow-hidden bg-white p-6 shadow-sm sm:rounded-lg">
                    <form method="get" action="" class="mb-4 flex space-x-2">
                        <input name="email" :value="searchEmail" class="rounded border-gray-300" placeholder="Search email" />
                        <button type="submit" class="rounded bg-blue-600 px-3 py-1 text-white">Search</button>
                    </form>
                    <div v-if="searchedCourses && searchedCourses.length">
                        <h3 class="mb-2 font-semibold">Courses for {{ searchEmail }}</h3>
                        <ul class="list-disc pl-5">
                            <li v-for="c in searchedCourses" :key="c.id">{{ c.title }}</li>
                        </ul>
                    </div>
                    <div v-else-if="searchEmail" class="text-gray-600">No courses found for this email.</div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
