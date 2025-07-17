<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

const props = defineProps({
    courses: Array,
});
</script>

<template>
    <Head title="Courses" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Courses
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white p-6 shadow-sm sm:rounded-lg">
                    <div v-if="courses.length === 0" class="text-gray-600">
                        No courses available.
                    </div>
                    <ul v-else class="list-disc pl-5">
                        <li v-for="course in courses" :key="course.id" class="mb-2">
                            <Link :href="route('courses.show', course.id)" class="text-blue-600 underline">
                                {{ course.title }}
                            </Link>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
