<template>
    <div class="container mx-auto p-4">
        <h1 class="text-2xl font-semibold mb-4">Users</h1>

        <div class="flex items-center justify-between gap-4 mb-4">
            <Link :href="route('register')"
                class="bg-green-500 text-white px-4 py-2 rounded cursor-pointer inline-block">
            + Add User
            </Link>

            <Link :href="route('logout')"
                class="inline-block rounded-sm border border-transparent px-5 py-1.5 text-sm leading-normal text-[#1b1b18] hover:border-[#19140035] dark:text-[#EDEDEC] dark:hover:border-[#3E3E3A]">
            Log out
            </Link>
        </div>

        <!-- Users Table -->
        <table class="min-w-full bg-gray-100  rounded-md shadow-md">
            <thead>
                <tr>
                    <th class="px-4 py-2 text-center">
                        Name
                    </th>

                    <th class="px-4 py-2 text-center">
                        Email
                    </th>

                    <th class="px-4 py-2 text-center">
                        Created At
                    </th>

                    <th class="px-4 py-2 text-center">
                        Updated At
                    </th>

                    <th class="px-4 py-2 text-center"></th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="user in users.data" :key="user.id">
                    <td class="px-4 py-2 align-top text-center">{{ user.name }}</td>
                    <td class="px-4 py-2 align-top text-center">{{ user.email }}</td>
                    <td class="px-4 py-2 align-top text-center">{{ formatDate(user.created_at) }}</td>
                    <td class="px-4 py-2 align-top text-center">{{ formatDate(user.updated_at) }}</td>
                    <td class="px-4 py-2 align-top text-center">
                        <Link :href="route('users.edit', user.id)"
                            class="bg-blue-500 text-white px-2 py-1 rounded-md cursor-pointer">
                        Edit
                        </Link>
                    </td>
                </tr>
            </tbody>
        </table>

        <Pagination :resource="users" />
    </div>
</template>

<script>
import { Link } from '@inertiajs/vue3';
import Pagination from '@/components/Pagination.vue';

export default {
    props: {
        users: Object
    },
    components: {
        Pagination,
        Link,
    },
    methods: {
        formatDate(date) {
            const options = {
                year: 'numeric',
                month: 'long',
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit',
            };
            return new Date(date).toLocaleString(undefined, options);
        }
    },
};
</script>

<style scoped></style>
