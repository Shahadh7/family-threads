<script setup>
import { ref, computed } from "vue";
import ApplicationLogo from "@/Components/ApplicationLogo.vue";
import Dropdown from "@/Components/Dropdown.vue";
import DropdownLink from "@/Components/DropdownLink.vue";
import NavLink from "@/Components/NavLink.vue";
import ResponsiveNavLink from "@/Components/ResponsiveNavLink.vue";
import { Link } from "@inertiajs/vue3";
import SideBarItem from "@/Components/SideBarItem.vue";
// import ApplicationLogo from '@/Components/ApplicationLogo.vue';

const showingNavigationDropdown = ref(false);


const imageAvailable = ref(true);

// Handle image error by showing the first letter instead
const handleImageError = () => {
    imageAvailable.value = false;
};

const currentActive = ref("Home");

</script>

<template>
    <div>
        <div class="min-h-screen">
            <div class="nav-section flex justify-between">
                <div class="w-full px-4 sm:px-6 lg:px-8 mt-3">
                    <div class="flex h-16 justify-between">
                        <ApplicationLogo />
                        <div class="hidden sm:ms-6 sm:flex sm:items-center">
                            <!-- Settings Dropdown -->
                            <div class="relative ms-3">
                                <Dropdown align="right" width="48">
                                    <template #trigger>
                                        <span class="inline-flex rounded-md">
                                            <template v-if="$page.props.auth.user.profile_picture !== null">
                                                <img
                                                    :src="$page.props.auth.user.profile_picture"
                                                    alt="Avatar"
                                                    class="h-14 w-14 rounded-full object-cover border-4 border-solid border-black"
                                                    @error="handleImageError"
                                                />
                                            </template>
                                            <template v-else>
                                                <span
                                                    class="h-14 w-14 rounded-full flex items-center justify-center bg-gray-500 text-white font-bold border-4 border-solid border-black"
                                                >
                                                    {{ $page.props.auth.user.name.charAt(0).toUpperCase() }}
                                                </span>
                                            </template>
                                        </span>
                                    </template>

                                    <template #content>
                                        <DropdownLink
                                            :href="route('profile.edit')"
                                        >
                                            Profile
                                        </DropdownLink>
                                        <DropdownLink
                                            :href="route('logout')"
                                            method="post"
                                            as="button"
                                        >
                                            Log Out
                                        </DropdownLink>
                                    </template>
                                </Dropdown>
                            </div>
                        </div>

                        <!-- Hamburger -->
                        <div class="-me-2 flex items-center sm:hidden">
                            <button
                                @click="
                                    showingNavigationDropdown =
                                        !showingNavigationDropdown
                                "
                                class="inline-flex items-center justify-center rounded-md p-2 text-gray-400 transition duration-150 ease-in-out hover:bg-gray-100 hover:text-gray-500 focus:bg-gray-100 focus:text-gray-500 focus:outline-none"
                            >
                                <svg
                                    class="h-6 w-6"
                                    stroke="currentColor"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        :class="{
                                            hidden: showingNavigationDropdown,
                                            'inline-flex':
                                                !showingNavigationDropdown,
                                        }"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M4 6h16M4 12h16M4 18h16"
                                    />
                                    <path
                                        :class="{
                                            hidden: !showingNavigationDropdown,
                                            'inline-flex':
                                                showingNavigationDropdown,
                                        }"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"
                                    />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="flex sm:px-6 lg:px-8 gap-8">
                <div class="sidebar  border-r-2 h-96 my-12">
                    <div class="flex flex-col gap-4">
                        <SideBarItem
                            :href="route('dashboard')"
                            :active="route().current('dashboard')"
                        >
                            Dashboard
                        </SideBarItem>
                        <SideBarItem
                            :href="route('family-tree.index')"
                            :active="route().current('family-tree.index')"
                        >
                            Family Tree
                        </SideBarItem>
                        <SideBarItem
                            :href="route('memory-items.index')"
                            :active="route().current('memory-items.index') || route().current('memory-items.create')"
                        >
                            Memory Items
                        </SideBarItem>
                        <SideBarItem
                            :href="route('time-thread.index')"
                            :active="route().current('time-thread.index')"
                        >
                            Time Thread
                        </SideBarItem>
                    </div>
                </div>
                <div class="page-content mt-10">
                    <main>
                        <header
                            class=""
                            v-if="$slots.header"
                        >
                            <div class="mx-auto montserrat-bold">
                                <slot name="header" />
                            </div>
                        </header>
                        <slot />
                    </main>
                </div>
            </div>
        </div>
    </div>
</template>
<style scoped>
.sidebar {
    width: 15%;
}
.page-content {
    width: 85%;
}
</style>
