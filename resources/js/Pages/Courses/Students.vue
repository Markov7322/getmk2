<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const props = defineProps({
    course: Object,
});

const form = useForm({
    identifier: '',
});

function addStudent() {
    form.post(route('courses.students.store', props.course.id), {
        preserveScroll: true,
        onSuccess: () => form.reset(),
    });
}
</script>

<template>
    <Head :title="course.title" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Students for {{ course.title }}
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white p-6 shadow-sm sm:rounded-lg">
                    <form @submit.prevent="addStudent" class="mb-4 flex space-x-2">
                        <input v-model="form.identifier" class="rounded border-gray-300" placeholder="ID or Name" />
                        <button type="submit" class="rounded bg-blue-600 px-3 py-1 text-white">Add</button>
                    </form>

                    <ul class="list-disc pl-5" v-if="course.students.length">
                        <li v-for="student in course.students" :key="student.id" class="mb-1 flex items-center justify-between">
                            <span>{{ student.name }} ({{ student.id }})</span>
                            <form :action="route('courses.students.destroy', [course.id, student.id])" method="post">
                                <input type="hidden" name="_method" value="delete" />
                                <button type="submit" class="text-red-600 underline">Remove</button>
                            </form>
                        </li>
                    </ul>
                    <div v-else class="text-gray-600">No students enrolled.</div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
