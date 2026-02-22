<template>
    <div class="layout">
        <Sidebar />

        <div class="main">
            <Navbar />
            <div class="content">
                <router-view />
            </div>
        </div>
    </div>
</template>

<script>
import Sidebar from '../components/Sidebar.vue'
import Navbar from '../components/Navbar.vue'

export default {
    components: {
        Sidebar,
        Navbar
    }
}
</script>

<style scoped>
.layout {
    display: flex;
    height: 100vh;
    font-family: Arial, sans-serif;
}

.main {
    flex: 1;
    display: flex;
    flex-direction: column;
}

.content {
    padding: 20px;
    background: #f4f6f9;
    flex: 1;
    overflow-y: auto;
}
</style>