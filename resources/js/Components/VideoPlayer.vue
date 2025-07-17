<script setup>
import { computed, ref, watch, onMounted } from 'vue';

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

const isDirectVideo = computed(() => /\.(mp4|webm|ogg)(\?.*)?$/i.test(props.src));

const videoRef = ref(null);
const videoSrc = ref('');
const isPlaying = ref(false);
const progress = ref(0);
const volume = ref(1);

const fetchVideo = async () => {
    const response = await fetch(props.src);
    const blob = await response.blob();
    videoSrc.value = URL.createObjectURL(blob);
};

onMounted(() => {
    if (isDirectVideo.value) {
        fetchVideo();
    }
});

watch(() => props.src, () => {
    if (isDirectVideo.value) {
        fetchVideo();
    }
});

const togglePlay = () => {
    const video = videoRef.value;
    if (!video) return;
    if (video.paused) {
        video.play();
        isPlaying.value = true;
    } else {
        video.pause();
        isPlaying.value = false;
    }
};

const updateProgress = () => {
    const video = videoRef.value;
    if (!video) return;
    progress.value = (video.currentTime / video.duration) * 100;
};

const seek = () => {
    const video = videoRef.value;
    if (!video) return;
    video.currentTime = (progress.value / 100) * video.duration;
};

const changeVolume = () => {
    const video = videoRef.value;
    if (!video) return;
    video.volume = volume.value;
};
</script>

<template>
    <div class="aspect-video w-full" @contextmenu.prevent>
        <iframe
            v-if="!isDirectVideo"
            :src="embedUrl"
            frameborder="0"
            allowfullscreen
            class="h-full w-full rounded"
        />
        <div v-else class="relative h-full w-full">
            <video
                ref="videoRef"
                :src="videoSrc"
                class="h-full w-full rounded"
                @timeupdate="updateProgress"
                @click="togglePlay"
            />
            <div class="absolute bottom-0 left-0 right-0 flex items-center space-x-2 bg-black/60 px-2 py-1 text-sm text-white">
                <button @click="togglePlay" class="px-2">{{ isPlaying ? 'Pause' : 'Play' }}</button>
                <input type="range" min="0" max="100" step="0.1" v-model="progress" @input="seek" class="flex-1" />
                <input type="range" min="0" max="1" step="0.05" v-model="volume" @input="changeVolume" class="w-24" />
            </div>
        </div>
    </div>
</template>
