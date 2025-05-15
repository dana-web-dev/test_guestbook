<template>


    <GuestbookLayout>

        <Head title="Users" />
        <div class="container mx-auto p-4">
            <h1 class="text-2xl font-semibold mb-4">Users</h1>

            <div class="flex items-center justify-between gap-4 mb-4">
                <Link :href="route('register')"
                    class="bg-green-500 text-white px-4 py-2 rounded cursor-pointer inline-block shadow-md">
                + Add User
                </Link>

                <Link
                    class="inline-block rounded-sm border border-transparent px-5 py-1.5 text-sm leading-normal text-[#1b1b18] hover:border-[#19140035] dark:text-[#EDEDEC] dark:hover:border-[#3E3E3A]"
                    method="post" :href="route('logout')" @click="handleLogout" as="button">
                Log out
                </Link>
            </div>

            <!-- Users Table -->
            <table class="min-w-full bg-white  rounded-md shadow-md">
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
                        <td class="px-4 py-2 align-top text-center">{{ user.created_at_formatted }}</td>
                        <td class="px-4 py-2 align-top text-center">{{ user.created_at_formatted }}</td>
                        <td class="px-4 py-2 align-top text-center">
                            <button v-if="users.data.length > 1" @click="deleteUser(user.id)"
                                class="bg-red-500 text-white px-2 py-1 rounded-md mb-2 cursor-pointer">
                                Delete
                            </button>
                            <button @click="editUser(user.id)"
                                class="bg-blue-500 text-white px-2 py-1 rounded-md ml-2 cursor-pointer">
                                Edit
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>

            <Pagination :resource="users" />
        </div>
    </GuestbookLayout>
</template>

<script>
import { Link, router, Head } from '@inertiajs/vue3';
import GuestbookLayout from '@/layouts/GuestbookLayout.vue'
import Pagination from '@/components/Pagination.vue';

export default {
    props: {
        users: Object
    },
    components: {
        Pagination,
        Link,
        GuestbookLayout,
        Head
    },
    methods: {
        handleLogout() {
            router.flushAll();
        },
        deleteUser(id) {
            if (confirm('Are you sure you want to delete this user?')) {
                this.$inertia.delete(`/users/${id}`);
            }
        },
        editUser(id) {
            this.$inertia.get(`/users/${id}/edit`);
        }
    },
};
</script>

<style scoped></style>
