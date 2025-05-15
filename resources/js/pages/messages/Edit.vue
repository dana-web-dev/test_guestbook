<template>
    <GuestbookLayout>

        <Head title="Edit Message" />
        <div class="max-w-lg mx-auto p-6 bg-white shadow rounded-lg mt-8">
            <h2 class="text-xl font-semibold mb-4">Edit Message</h2>

            <form @submit.prevent="submit" enctype="multipart/form-data">
                <div class="mb-4">
                    <label class="block mb-1 font-medium">Name</label>
                    <input v-model="form.name" type="text" class="w-full border p-2 rounded" maxlength="255" required />
                </div>
                <div class="mb-4">
                    <label class="block mb-1 font-medium">Email</label>
                    <input v-model="form.email" type="email" class="w-full border p-2 rounded" maxlength="255"
                        required />
                </div>
                <div class="mb-4">
                    <label class="block mb-1 font-medium">Message</label>
                    <textarea v-model="form.message" class="w-full border p-2 rounded" maxlength="1000"
                        required></textarea>
                </div>
                <div class="mb-4">
                    <label class="block mb-1 font-medium">Image (optional)</label>
                    <input type="file" accept=".jpg,.jpeg,.png" @change="handleFile" />

                    <div v-if="message.image" class="mt-2">
                        <p class="text-sm text-gray-600">Current image:</p>
                        <img :src="`/storage/${message.image}`" alt="Current Image" class="max-w-32 rounded-md mt-1" />

                        <label class="flex items-center mt-2 text-sm">
                            <input type="checkbox" v-model="form.delete_image" class="mr-2" />
                            Delete current image
                        </label>
                    </div>
                </div>
                <div class="mb-4">
                    <label class="block mb-1 font-medium">CAPTCHA</label>
                    <div ref="recaptcha"></div>
                </div>

                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Update</button>
            </form>
        </div>
    </GuestbookLayout>
</template>

<script setup>
import { useForm, usePage, Head } from '@inertiajs/vue3'
import { ref, computed, onMounted } from 'vue'
import GuestbookLayout from '@/layouts/GuestbookLayout.vue'

const recaptcha = ref(null)
const siteKey = computed(() => usePage().props.captchaSiteKey)

const props = defineProps({
    message: Object,
})

const form = useForm({
    name: props.message.name,
    email: props.message.email,
    message: props.message.message,
    image: null,
    delete_image: false,
    captcha: '',
})

function handleFile(e) {
    form.image = e.target.files[0]
}

function submit() {
    if (!form.captcha) {
        alert('Please complete the CAPTCHA');
        return;
    }

    console.log(form.data());

    form.post(route('messages.update', props.message.id), {
        _method: 'patch',
        preserveScroll: true,
        onSuccess: () => { },
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
