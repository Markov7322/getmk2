<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import InputLabel from '@/Components/InputLabel.vue';
import InputError from '@/Components/InputError.vue';

const form = useForm({
    title: '',
    price: 0,
    modules: [],
});

function addModule() {
    form.modules.push({ title: '', description: '', lessons: [] });
}

function addLesson(module) {
    module.lessons.push({ title: '', description: '', video_url: '', pdf: null });
}

function submit() {
    form.post(route('courses.store'), {
        preserveScroll: true,
        forceFormData: true,
        onSuccess: () => form.reset(),
    });
}
</script>

<template>
    <Head title="Create Course" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Create Course
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white p-6 shadow-sm sm:rounded-lg">
                    <form @submit.prevent="submit">
                        <div class="mb-4">
                            <InputLabel value="Course Title" />
                            <TextInput v-model="form.title" class="mt-1 block w-full" />
                            <InputError :message="form.errors.title" class="mt-2" />
                        </div>
                        <div class="mb-4">
                            <InputLabel value="Price" />
                            <TextInput type="number" v-model="form.price" class="mt-1 block w-full" />
                            <InputError :message="form.errors.price" class="mt-2" />
                        </div>
                        <div v-for="(module, mIndex) in form.modules" :key="mIndex" class="mb-4 border p-2">
                            <InputLabel value="Module Title" />
                            <TextInput v-model="module.title" class="mt-1 block w-full" />
                            <InputError :message="form.errors[`modules.${mIndex}.title`]" class="mt-2" />
                            <InputLabel value="Module Description" class="mt-2" />
                            <textarea v-model="module.description" class="mt-1 block w-full rounded-md border-gray-300"></textarea>
                            <InputError :message="form.errors[`modules.${mIndex}.description`]" class="mt-2" />
                            <div class="ml-4 mt-2" v-for="(lesson, lIndex) in module.lessons" :key="lIndex">
                                <InputLabel value="Lesson Title" />
                                <TextInput v-model="lesson.title" class="mt-1 block w-full" />
                                <InputError :message="form.errors[`modules.${mIndex}.lessons.${lIndex}.title`]" class="mt-2" />
                                <InputLabel value="Lesson Description" class="mt-2" />
                                <textarea v-model="lesson.description" class="mt-1 block w-full rounded-md border-gray-300"></textarea>
                                <InputError :message="form.errors[`modules.${mIndex}.lessons.${lIndex}.description`]" class="mt-2" />
                                <InputLabel value="Video URL" class="mt-2" />
                                <TextInput v-model="lesson.video_url" class="mt-1 block w-full" />
                                <InputError :message="form.errors[`modules.${mIndex}.lessons.${lIndex}.video_url`]" class="mt-2" />
                                <InputLabel value="PDF" class="mt-2" />
                                <input type="file" accept="application/pdf" @change="e => lesson.pdf = e.target.files[0]" class="mt-1 block w-full" />
                                <InputError :message="form.errors[`modules.${mIndex}.lessons.${lIndex}.pdf`]" class="mt-2" />
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
