<template>
    <div class="min-h-screen bg-gray-100">
        <header class="bg-white shadow p-4 flex items-center justify-between">
            <h1 class="text-xl font-semibold">
                <Link :href="route('messages.index')">Guestbook</Link>
            </h1>

            <div class="relative" @click="toggleDropdown">
                <button class="bg-gray-200 px-4 py-2 rounded hover:bg-gray-300 focus:outline-none">
                    Menu
                </button>
                <div v-if="dropdownOpen" class="absolute right-0 mt-2 w-48 bg-white shadow rounded z-10">
                    <Link :href="route('messages.index')" class="block px-4 py-2 hover:bg-gray-100">Messages</Link>
                    <Link :href="route('messages.create')" class="block px-4 py-2 hover:bg-gray-100">Add Message</Link>
                    <template v-if="user">
                        <Link :href="route('users.index')" class="block px-4 py-2 hover:bg-gray-100">Users
                        </Link>
                        <Link :href="route('register')" class="block px-4 py-2 hover:bg-gray-100">Add User
                        </Link>
                        <Link :href="route('logout')" method="post" as="button"
                            class="block px-4 py-2 hover:bg-gray-100 w-full text-left">
                        Log out
                        </Link>
                    </template>
                    <template v-else>
                        <Link :href="route('login')" class="block px-4 py-2 hover:bg-gray-100">
                        Log in
                        </Link>
                    </template>
                </div>
            </div>
        </header>

        <main class="p-6">
            <slot />
        </main>
    </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import { Link, usePage } from '@inertiajs/vue3'

const dropdownOpen = ref(false)
function toggleDropdown() {
    dropdownOpen.value = !dropdownOpen.value
}

const user = computed(() => usePage().props.auth?.user)
</script>
