<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import InputLabel from '@/Components/InputLabel.vue';
import InputError from '@/Components/InputError.vue';

const props = defineProps({
    courses: Array,
});

const form = useForm({
    title: '',
    modules: [],
});

function addModule() {
    form.modules.push({ title: '', lessons: [] });
}

function addLesson(module) {
    module.lessons.push({ title: '' });
}

function submit() {
    form.post(route('courses.store'), {
        preserveScroll: true,
        onSuccess: () => form.reset(),
    });
}
</script>

<template>
    <Head title="My Courses" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                My Courses
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
                            <div class="font-bold">{{ course.title }}</div>
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

                    <form @submit.prevent="submit">
                        <h3 class="mb-2 font-semibold">Create Course</h3>
                        <div class="mb-4">
                            <InputLabel value="Course Title" />
                            <TextInput v-model="form.title" class="mt-1 block w-full" />
                            <InputError :message="form.errors.title" class="mt-2" />
                        </div>
                        <div v-for="(module, mIndex) in form.modules" :key="mIndex" class="mb-4 border p-2">
                            <InputLabel value="Module Title" />
                            <TextInput v-model="module.title" class="mt-1 block w-full" />
                            <InputError :message="form.errors[`modules.${mIndex}.title`]" class="mt-2" />
                            <div class="ml-4 mt-2" v-for="(lesson, lIndex) in module.lessons" :key="lIndex">
                                <InputLabel value="Lesson Title" />
                                <TextInput v-model="lesson.title" class="mt-1 block w-full" />
                                <InputError :message="form.errors[`modules.${mIndex}.lessons.${lIndex}.title`]" class="mt-2" />
                            </div>
                            <PrimaryButton type="button" class="mt-2" @click="addLesson(module)">
                                Add Lesson
                            </PrimaryButton>
                        </div>
                        <PrimaryButton type="button" class="mb-4" @click="addModule">
                            Add Module
                        </PrimaryButton>
                        <div>
                            <PrimaryButton :disabled="form.processing">
                                Save
                            </PrimaryButton>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
