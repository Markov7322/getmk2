<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

const props = defineProps({
    courses: Array,
});

</script>

<template>
    <Head title="Enrolled Courses" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Enrolled Courses
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white p-6 shadow-sm sm:rounded-lg">
                    <div v-if="courses.length === 0" class="mb-4 text-gray-600">
                        No courses yet.
                    </div>
                    <div v-else class="mb-6">
                        <div v-for="course in courses" :key="course.id" class="mb-4">
                            <Link :href="route('courses.show', course.id)" class="font-bold text-blue-600 underline">
                                {{ course.title }}
                            </Link>
                            <ul class="ml-4 list-disc">
                                <li v-for="module in course.modules" :key="module.id">
                                    {{ module.title }}
                                    <ul class="ml-4 list-disc">
                                        <li v-for="lesson in module.lessons" :key="lesson.id">
                                            {{ lesson.title }}
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
