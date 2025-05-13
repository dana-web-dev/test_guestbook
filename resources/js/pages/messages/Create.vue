<template>
    <form @submit.prevent="submit">
        <input v-model="form.name" placeholder="Your Name" required />
        <input v-model="form.email" placeholder="Your Email" required />
        <textarea v-model="form.message" placeholder="Your Message" required></textarea>
        <input type="file" @change="handleFileUpload" />
        <!-- <captcha-component></captcha-component> -->
        <button type="submit">Submit</button>
    </form>
</template>

<script>
import { Inertia } from '@inertiajs/inertia';
import { useForm } from '@inertiajs/inertia-vue3';

export default {
    data() {
        return {
            form: useForm({
                name: '',
                email: '',
                message: '',
                image: null,
                //   captcha: '', 
            }),
        };
    },
    methods: {
        handleFileUpload(event) {
            this.form.image = event.target.files[0];
        },

        submit() {
            this.form.post('/messages', {
                onSuccess: () => {
                    console.log('Message successfully submitted'); // TODO delete
                    // this.$inertia.visit('/messages');
                },
                onError: (errors) => {
                    console.error(errors);
                },
            });
        },
    },
};
</script>