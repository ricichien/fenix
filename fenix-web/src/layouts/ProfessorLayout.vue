<template>
    <div class="layout">
        <aside class="sidebar">
            <h2>Professor</h2>

            <nav>
                <RouterLink to="/professor">Dashboard</RouterLink>
                <RouterLink to="/professor/exams">Provas</RouterLink>
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
}

.sidebar {
    width: 220px;
    background: #1e1e1e;
    color: white;
    padding: 20px;
    height: 100vh;
}

.content {
    flex: 1;
    padding: 20px;
}
</style>