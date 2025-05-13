<template>
    <div class="container mx-auto p-4">
        <h1 class="text-2xl font-semibold mb-4">Guestbook</h1>

        <table class="min-w-full bg-gray-100  rounded-md shadow-md">
            <thead>
                <tr>
                    <th class="px-4 py-2 text-center">Name</th>
                    <th class="px-4 py-2 text-center">Message</th>
                    <th class="px-4 py-2 text-center min-w-32">Created At</th>
                    <th class="px-4 py-2 text-center"></th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="message in messages.data" :key="message.id">
                    <td class="px-4 py-2">
                        {{ message.name }}&nbsp;<a :href="'mailto:' + message.email" class="text-blue-500">{{
                            message.email }}</a>
                    </td>
                    <td class="px-4 py-2">
                        <p>{{ message.message }}</p>
                        <div v-if="message.image">
                            <a :href="'/storage/' + message.image" target="_blank">
                                <img :src="'/storage/' + message.image" alt="Image Preview"
                                    class="max-w-32 h-auto mt-2">
                            </a>
                        </div>
                        <p v-if="message.updated_at && message.created_at !== message.updated_at"
                            class="text-sm text-gray-500">Edited: {{
                                formatDate(message.updated_at) }}</p>
                    </td>
                    <td class="px-4 py-2">{{ formatDate(message.created_at) }}</td>
                    <td class="px-4 py-2">
                        <button v-if="message.user_ip === userIp && isWithinFiveMinutes(message.created_at)"
                            @click="deleteMessage(message.id)"
                            class="bg-red-500 text-white px-2 py-1 rounded-md mb-2 cursor-pointer">
                            Delete
                        </button>
                        <button @click="editMessage(message.id)"
                            class="bg-blue-500 text-white px-2 py-1 rounded-md ml-2 cursor-pointer">
                            Edit
                        </button>
                    </td>
                </tr>
            </tbody>
        </table>

        <Pagination :resource="messages" />
    </div>
</template>

<script>
import Pagination from '@/components/Pagination.vue';

export default {
    props: {
        messages: Object,
        userIp: String,
    },
    components: {
        Pagination,
    },
    methods: {
        formatDate(date) {
            const options = { year: 'numeric', month: 'long', day: 'numeric', hour: '2-digit', minute: '2-digit' };
            return new Date(date).toLocaleString(undefined, options);
        },
        changePage(url) {
            if (!url) return;
            this.$inertia.get(url);
        },
        deleteMessage(id) {
            if (confirm('Are you sure you want to delete this message?')) {
                this.$inertia.delete(`/messages/${id}`);
            }
        },
        editMessage(id) {
            this.$inertia.get(`/messages/${id}/edit`);
        },
        isWithinFiveMinutes(createdAt) {
            const created = new Date(createdAt);
            const now = new Date();
            const diffMs = now - created;
            return diffMs < 5 * 60 * 1000; // 5 minutes in milliseconds
        },
    }
};
</script>

<style scoped></style>