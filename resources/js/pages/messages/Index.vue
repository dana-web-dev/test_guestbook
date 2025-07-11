<template>
    <GuestbookLayout>

        <Head title="Messages" />
        <div class="container mx-auto p-4">
            <div class="flex items-center justify-between gap-4 mb-4">
                <Link :href="route('messages.create')"
                    class="bg-green-500 text-white px-4 py-2 rounded cursor-pointer inline-block shadow-md">
                + Add message
                </Link>
            </div>


            <table class="min-w-full bg-white  rounded-md shadow-md">
                <thead>
                    <tr>
                        <th class="px-4 py-2 text-center cursor-pointer flex items-center justify-center gap-1"
                            @click="sortBy('name')">
                            Name
                            <ArrowDownWideNarrow v-if="sortDirection === 'desc'"
                                :class="sort === 'name' ? 'text-blue-500' : 'text-gray-500'" :size="20" />
                            <ArrowDownNarrowWide v-else :class="sort === 'name' ? 'text-blue-500' : 'text-gray-500'"
                                :size="20" />
                        </th>

                        <th class="px-4 py-2 text-center">Message</th>
                        <th class="px-4 py-2 text-center min-w-48 cursor-pointer flex items-center justify-center gap-1"
                            @click="sortBy('created_at')">
                            Created At
                            <ArrowDownWideNarrow v-if="sortDirection === 'desc'"
                                :class="sort === 'created_at' ? 'text-blue-500' : 'text-gray-500'" :size="20" />
                            <ArrowDownNarrowWide v-else
                                :class="sort === 'created_at' ? 'text-blue-500' : 'text-gray-500'" :size="20" />
                        </th>

                        <th class="px-4 py-2 text-center"></th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="message in messages.data" :key="message.id">
                        <td class="px-4 py-2 align-top">
                            {{ message.name }}&nbsp;<a :href="'mailto:' + message.email" class="text-blue-500">{{
                                message.email }}</a>
                        </td>
                        <td class="px-4 py-2 align-top">
                            <p>{{ message.message }}</p>
                            <div v-if="message.image">
                                <a :href="'/storage/' + message.image" target="_blank">
                                    <img :src="'/storage/' + message.image" alt="Image Preview"
                                        class="max-w-32 h-auto mt-2 rounded-md">
                                </a>
                            </div>
                            <p v-if="message.updated_at && message.created_at !== message.updated_at"
                                class="text-sm text-gray-500">Edited: {{
                                    message.updated_at_formatted }}</p>
                        </td>
                        <td class="px-4 py-2 align-top">{{ message.created_at_formatted }}</td>
                        <td class="px-4 py-2 align-top">
                            <div v-if="user || (message.user_ip === userIp && isWithinFiveMinutes(message.created_at))">
                                <button @click="deleteMessage(message.id)"
                                    class="bg-red-500 text-white px-2 py-1 rounded-md mb-2 cursor-pointer">
                                    Delete
                                </button>
                                <button @click="editMessage(message.id)"
                                    class="bg-blue-500 text-white px-2 py-1 rounded-md ml-2 cursor-pointer">
                                    Edit
                                </button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>

            <Pagination :resource="messages" />
        </div>
    </GuestbookLayout>
</template>

<script>
import Pagination from '@/components/Pagination.vue';
import { ArrowDownNarrowWide, ArrowDownWideNarrow } from 'lucide-vue-next';
import { Link, usePage, Head } from '@inertiajs/vue3';
import GuestbookLayout from '@/layouts/GuestbookLayout.vue'
import { computed } from 'vue'

const user = computed(() => usePage().props.auth?.user)

export default {
    props: {
        messages: Object,
        userIp: String,
        sort: String,
        sortDirection: String,
    },
    components: {
        Pagination,
        ArrowDownNarrowWide,
        ArrowDownWideNarrow,
        Link,
        GuestbookLayout,
        Head
    },
    data() {
        return {
            showModal: false,
        };
    },
    computed: {
        user() {
            return usePage().props.auth?.user || null;
        },
    },
    methods: {
        openModal() {
            this.showModal = true;
        },
        closeModal() {
            this.showModal = false;
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
            return diffMs < 5 * 60 * 1000;
        },
        sortBy(column) {
            let sortDirection =
                this.sort === column && this.sortDirection === 'asc' ? 'desc' : 'asc';
            this.$inertia.get(this.$page.url, { sort: column, sortDirection });
        },
    },
};
</script>


<style scoped></style>