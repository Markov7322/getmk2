<script setup>
import { computed } from 'vue';

const props = defineProps({
    src: {
        type: String,
        required: true
    }
});

const embedUrl = computed(() => {
    if (!props.src) return '';
    const ytMatch = props.src.match(/(?:youtube\.com.*[?&]v=|youtu\.be\/)([\w-]+)/i);
    if (ytMatch) {
        return `https://www.youtube.com/embed/${ytMatch[1]}`;
    }
    const vimeoMatch = props.src.match(/vimeo\.com\/(\d+)/i);
    if (vimeoMatch) {
        return `https://player.vimeo.com/video/${vimeoMatch[1]}`;
    }
    return props.src;
});
</script>

<template>
    <div class="aspect-video w-full">
        <iframe
            v-if="embedUrl !== src"
            :src="embedUrl"
            frameborder="0"
            allowfullscreen
            class="h-full w-full rounded"
        />
        <video
            v-else
            controls
            class="h-full w-full rounded"
        >
            <source :src="src" />
            Your browser does not support the video tag.
        </video>
    </div>
</template>
