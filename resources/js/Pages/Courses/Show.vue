<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import VideoPlayer from '@/Components/VideoPlayer.vue';

const props = defineProps({
    course: Object,
});
</script>

<template>
    <Head :title="course.title" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                {{ course.title }}
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white p-6 shadow-sm sm:rounded-lg">
                    <p class="mb-6" v-if="course.description">{{ course.description }}</p>

                    <div v-for="module in course.modules" :key="module.id" class="mb-6">
                        <h3 class="text-lg font-bold">{{ module.title }}</h3>
                        <p class="mb-2 text-gray-600" v-if="module.description">{{ module.description }}</p>
                        <ul class="ml-4 list-disc">
                            <li v-for="lesson in module.lessons" :key="lesson.id" class="mb-3">
                                <div class="font-medium">{{ lesson.title }}</div>
                                <p class="text-sm text-gray-600" v-if="lesson.description">{{ lesson.description }}</p>
                                <div v-if="lesson.video_url" class="mt-3">
                                    <VideoPlayer :src="lesson.video_url" />
                                </div>
                                <div v-if="lesson.pdf_path" class="mt-1">
                                    <a :href="lesson.pdf_path" target="_blank" class="text-blue-600 underline">Download PDF</a>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
