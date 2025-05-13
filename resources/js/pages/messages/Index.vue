<template>
    <div class="container mx-auto p-4">
        <h1 class="text-2xl font-semibold mb-4">Messages</h1>

        <table class="min-w-full bg-white border border-gray-300 rounded-lg shadow-md">
            <thead>
                <tr>
                    <th class="px-4 py-2 text-left">Name</th>
                    <th class="px-4 py-2 text-left">Message</th>
                    <th class="px-4 py-2 text-left">Created At</th>
                    <th class="px-4 py-2 text-left"></th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="message in messages.data" :key="message.id">
                    <td class="px-4 py-2">
                        <a :href="'mailto:' + message.email" class="text-blue-500">{{ message.name }}</a>
                    </td>
                    <td class="px-4 py-2">
                        <p>{{ message.message }}</p>
                        <div v-if="message.image">
                            <a :href="'/storage/' + message.image" target="_blank">
                                <img :src="'/storage/' + message.image" alt="Image Preview"
                                    class="max-w-32 h-auto mt-2">
                            </a>
                        </div>
                        <p v-if="message.updated_at">Edited: {{ formatDate(message.updated_at) }}</p>
                    </td>
                    <td class="px-4 py-2">{{ formatDate(message.created_at) }}</td>
                    <td class="px-4 py-2">
                        <button v-if="message.user_ip === userIp" @click="deleteMessage(message.id)"
                            class="bg-red-500 text-white px-2 py-1 rounded-md">
                            Delete
                        </button>
                        <button @click="editMessage(message.id)"
                            class="bg-blue-500 text-white px-2 py-1 rounded-md ml-2">
                            Edit
                        </button>
                    </td>
                </tr>
            </tbody>
        </table>

        <!-- Pagination -->
        <div class="mt-4">
            <button @click="changePage(messages.prev_page_url)" :disabled="!messages.prev_page_url"
                class="px-4 py-2 bg-gray-200 rounded-md mr-2">
                Previous
            </button>
            <button @click="changePage(messages.next_page_url)" :disabled="!messages.next_page_url"
                class="px-4 py-2 bg-gray-200 rounded-md">
                Next
            </button>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        messages: Object,
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
        }
    }
};
</script>

<style scoped></style>