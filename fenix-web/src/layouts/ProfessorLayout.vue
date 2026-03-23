<script setup lang="ts">
import { RouterLink, RouterView } from "vue-router";
</script>

<template>
    <div class="layout">
        <aside class="sidebar">
            <h2>Professor</h2>

            <nav>
                <RouterLink to="/professor">Dashboard</RouterLink>
                <RouterLink to="/professor/exams">Provas</RouterLink>
                <RouterLink to="/professor/exams/new">Nova prova</RouterLink>
            </nav>
        </aside>

        <main class="content">
            <RouterView />
        </main>
    </div>
</template>

<style scoped>
.layout {
    display: flex;
    min-height: 100vh;
}

.sidebar {
    width: 240px;
    padding: 20px;
    background: #1f1f1f;
    color: white;
}

.sidebar nav {
    display: flex;
    flex-direction: column;
    gap: 12px;
    margin-top: 20px;
}

.sidebar a {
    color: white;
    text-decoration: none;
}

.content {
    flex: 1;
    padding: 24px;
}
</style>