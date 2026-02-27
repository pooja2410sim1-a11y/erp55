<template>
    <section class="dashboard">
        <h1>Dashboard</h1>
        <p>Modern control center for your export ERP modules.</p>

        <div class="tiles">
            <div class="tile">
                <span>Users Module</span>
                <strong>{{ hasPermission('user.view') ? 'Enabled' : 'Restricted' }}</strong>
            </div>
            <div class="tile">
                <span>Roles Module</span>
                <strong>{{ hasPermission('role.view') ? 'Enabled' : 'Restricted' }}</strong>
            </div>
            <div class="tile">
                <span>Permission Module</span>
                <strong>{{ hasPermission('manage_permissions') ? 'Enabled' : 'Restricted' }}</strong>
            </div>
        </div>
    </section>
</template>

<script setup>
import { hasPermission } from '../auth'
</script>

<style scoped>
.dashboard {
    background:#0b1328;
    border:1px solid #263b62;
    border-radius:14px;
    padding:18px;
}

h1 { margin:0; color:#f3f7ff; }
p { margin:8px 0 16px; color:#93a8cf; }

.tiles {
    display:grid;
    grid-template-columns:repeat(3,minmax(180px,1fr));
    gap:12px;
}

.tile {
    background:#101b35;
    border:1px solid #2b4068;
    border-radius:12px;
    padding:14px;
    display:flex;
    flex-direction:column;
    gap:8px;
}

span { color:#9eb4dc; }
strong { color:#f3f8ff; font-size:22px; }
</style>
