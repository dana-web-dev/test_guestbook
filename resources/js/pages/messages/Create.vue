<template>
    <div class="max-w-lg mx-auto p-6 bg-white shadow rounded-lg mt-8">
        <h2 class="text-xl font-semibold mb-4">Leave a Message</h2>

        <form @submit.prevent="submit">
            <div class="mb-4">
                <label class="block mb-1 font-medium">Name</label>
                <input v-model="form.name" type="text" class="w-full border p-2 rounded" maxlength="255" required />
            </div>
            <div class="mb-4">
                <label class="block mb-1 font-medium">Email</label>
                <input v-model="form.email" type="email" class="w-full border p-2 rounded" maxlength="255" required />
            </div>
            <div class="mb-4">
                <label class="block mb-1 font-medium">Message</label>
                <textarea v-model="form.message" class="w-full border p-2 rounded" maxlength="1000" required></textarea>
            </div>
            <div class="mb-4">
                <label class="block mb-1 font-medium">Image (optional)</label>
                <input type="file" @change="handleFile" />
            </div>
            <div class="mb-4">
                <label class="block mb-1 font-medium">CAPTCHA</label>
                <div ref="recaptcha"></div>
            </div>

            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Send</button>
        </form>
    </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue'
import { useForm, usePage } from '@inertiajs/vue3'

const recaptcha = ref(null)
const siteKey = computed(() => usePage().props.captchaSiteKey)

const form = useForm({
    name: '',
    email: '',
    message: '',
    image: null,
    captcha: '',
})

function handleFile(e) {
    form.image = e.target.files[0]
}

function submit() {
    form.post('/messages', {
        onSuccess: () => form.reset(),
    })
}

onMounted(() => {
    window.grecaptcha.ready(() => {
        if (window.grecaptcha && recaptcha.value) {
            window.grecaptcha.render(recaptcha.value, {
                sitekey: siteKey.value,
                callback: (response) => {
                    form.captcha = response
                },
            })
        }
    });
})
</script>
