<template>
    <div class="mt-4 flex items-center justify-between">
        <!-- Previous Button -->
        <button @click="changePage(resource.first_page_url)" :disabled="!resource.first_page_url"
            class="px-4 py-2 bg-gray-200 rounded-md mr-2 cursor-pointer">
            First
        </button>

        <!-- Page Numbers -->
        <div class="flex items-center space-x-2">
            <button v-if="resource.prev_page_url" @click="changePage(resource.prev_page_url)"
                class="px-4 py-2 bg-gray-200 rounded-md cursor-pointer">
                Previous
            </button>
            <button v-for="page in pageNumbers" :key="page" @click="changePage(pageUrl(page))"
                :class="{ 'bg-blue-500 text-white': resource.current_page === page, 'text-blue-500': resource.current_page !== page }"
                class="px-3 py-2 border rounded-md cursor-pointer">
                {{ page }}
            </button>
            <!-- Next Button -->
            <button v-if="resource.next_page_url" @click="changePage(resource.next_page_url)"
                class="px-4 py-2 bg-gray-200 rounded-md cursor-pointer">
                Next
            </button>
        </div>
        <button @click="changePage(resource.last_page_url)" :disabled="!resource.last_page_url"
            class="px-4 py-2 bg-gray-200 rounded-md cursor-pointer">
            Last
        </button>
    </div>
</template>

<script>
export default {
    props: {
        resource: Object,
    },
    computed: {
        pageNumbers() {
            // Get the current page and calculate the page range to display
            const currentPage = this.resource.current_page;
            const totalPages = this.resource.last_page;
            const maxPageLinks = 5;

            // Calculate the start and end page numbers to display
            const start = Math.max(currentPage - 2, 1);
            const end = Math.min(start + maxPageLinks - 1, totalPages);

            // Adjust the start page if we're showing fewer than 5 pages
            const adjustedStart = Math.max(end - maxPageLinks + 1, 1);

            // Return the array of page numbers to display
            return Array.from({ length: end - adjustedStart + 1 }, (_, i) => adjustedStart + i);
        }
    },
    methods: {
        changePage(url) {
            if (!url) return;
            this.$inertia.get(url);
        },
        pageUrl(page) {
            // Construct the URL to the desired page
            const pageUrl = new URL(this.resource.first_page_url);
            pageUrl.searchParams.set('page', page);
            return pageUrl.toString();
        }
    }
};
</script>

<style scoped></style>
