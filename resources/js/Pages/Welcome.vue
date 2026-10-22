<script setup>
import { Head, Link } from '@inertiajs/inertia-vue3';

defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
    laravelVersion: String,
    phpVersion: String,
})
</script>

<template>
    <Head title="Welcome" />

    <div>
        
        <div class="text-red-400">
            this text is red
        </div>

        <div class="h-[300px] py-4 bg-tdhh-orange relative">
            <div class="relative inset-0 z-20"></div>
            <div class="opacity-50 absolute inset-0 z-10 w-full h-full overflow-hidden">
                <img src="/img/bgs/home-bg.jpg" alt="tdhh" class="object-cover" />
            </div>
        </div>



    </div>
</template>
