<template>
    <div class="layout-shell">
        <Sidebar />

        <div class="main">
            <Navbar />
            <main class="content">
                <router-view />
            </main>
        </div>
    </div>
</template>

<script>
import Sidebar from '../components/Sidebar.vue'
import Navbar from '../components/Navbar.vue'

export default {
    components: {
        Sidebar,
        Navbar,
    },
}
</script>

<style scoped>
.layout-shell {
    display: flex;
    min-height: 100vh;
    background: #050a16;
    color: #dbe6ff;
    font-family: Inter, 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

.main {
    flex: 1;
    display: flex;
    flex-direction: column;
    min-width: 0;
}

.content {
    flex: 1;
    overflow-y: auto;
    padding: 24px;
    background: radial-gradient(circle at top, #0f1b37 0%, #050a16 58%);
}
</style>
