<template>
    <div class="student-app">
        <aside class="student-sidebar">
            <div class="logo-area">
                <div class="logo-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5" stroke-linecap="round"
                            stroke-linejoin="round" />
                    </svg>
                </div>
                <span class="logo-text">Fênix<strong>Student</strong></span>
            </div>

            <nav class="main-nav">
                <RouterLink to="/student/dashboard" class="nav-link" active-class="active">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="3" y="3" width="7" height="7" />
                        <rect x="14" y="3" width="7" height="7" />
                        <rect x="14" y="14" width="7" height="7" />
                        <rect x="3" y="14" width="7" height="7" />
                    </svg>
                    Início
                </RouterLink>
                <RouterLink to="/student/classes" class="nav-link">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M22 19a2 2 0 01-2 2H4a2 2 0 01-2-2V5a2 2 0 012-2h5l2 3h9a2 2 0 012 2z" />
                    </svg>
                    Minhas Turmas
                </RouterLink>
                <RouterLink to="/student/exams" class="nav-link" active-class="active">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7" />
                        <path d="M18.5 2.5a2.121 2.121 0 113 3L12 15l-4 1 1-4 9.5-9.5z" />
                    </svg>
                    Avaliações
                </RouterLink>
                <RouterLink to="/student/grades" class="nav-link">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M12 20V10M18 20V4M6 20v-4" />
                    </svg>
                    Desempenho
                </RouterLink>
            </nav>

            <div class="sidebar-bottom">
                <button class="settings-btn">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M12 15a3 3 0 100-6 3 3 0 000 6z" />
                        <path
                            d="M19.4 15a1.65 1.65 0 00.33 1.82l.06.06a2 2 0 010 2.83 2 2 0 01-2.83 0l-.06-.06a1.65 1.65 0 00-1.82-.33 1.65 1.65 0 00-1 1.51V21a2 2 0 01-2 2 2 2 0 01-2-2v-.09A1.65 1.65 0 009 19.4a1.65 1.65 0 00-1.82.33l-.06.06a2 2 0 01-2.83 0 2 2 0 010-2.83l.06-.06a1.65 1.65 0 00.33-1.82 1.65 1.65 0 00-1.51-1H3a2 2 0 01-2-2 2 2 0 012-2h.09A1.65 1.65 0 004.6 9a1.65 1.65 0 00-.33-1.82l-.06-.06a2 2 0 010-2.83 2 2 0 012.83 0l.06.06a1.65 1.65 0 001.82.33H9a1.65 1.65 0 001-1.51V3a2 2 0 012-2 2 2 0 012 2v.09a1.65 1.65 0 001 1.51 1.65 1.65 0 001.82-.33l.06-.06a2 2 0 012.83 0 2 2 0 010 2.83l-.06.06a1.65 1.65 0 00-.33 1.82V9a1.65 1.65 0 001.51 1H21a2 2 0 012 2 2 2 0 01-2 2h-.09a1.65 1.65 0 00-1.51 1z" />
                    </svg>
                    Ajustes
                </button>
            </div>
        </aside>

        <main class="content-area">
            <header class="top-nav">
                <div class="search-bar">
                    <svg class="search-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="11" cy="11" r="8" />
                        <path d="M21 21l-4.35-4.35" />
                    </svg>
                    <input type="text" placeholder="Buscar provas, matérias ou colegas...">
                </div>

                <div class="user-actions">
                    <div class="notif-badge">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M18 8a6 6 0 00-12 0c0 7-3 9-3 9h18s-3-2-3-9m-5 13a2 2 0 01-4 0" />
                        </svg>
                        <span class="dot"></span>
                    </div>
                    <div class="divider"></div>
                    <div class="student-info">
                        <div class="avatar-circle">JS</div>
                        <span class="user-name">João Silva</span>
                    </div>
                </div>
            </header>

            <section class="page-container">
                <RouterView />
            </section>
        </main>
    </div>
</template>

<style scoped>
.student-app {
    display: flex;
    min-height: 100vh;
    background: #f0f2f5;
    font-family: 'Inter', sans-serif;
}

.student-sidebar {
    width: 260px;
    background: #ffffff;
    border-right: 1px solid #e2e8f0;
    display: flex;
    flex-direction: column;
    padding: 24px 16px;
    position: sticky;
    top: 0;
    height: 100vh;
}

.logo-area {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 40px;
    padding: 0 8px;
}

.logo-icon {
    width: 36px;
    height: 36px;
    background: #ea580c;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
}

.logo-icon svg {
    width: 22px;
    height: 22px;
}

.logo-text {
    font-size: 18px;
    font-weight: 500;
    color: #1e293b;
}

.main-nav {
    flex: 1;
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.nav-link {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px 16px;
    text-decoration: none;
    color: #64748b;
    font-weight: 500;
    border-radius: 10px;
    transition: 0.2s;
}

.nav-link svg {
    width: 20px;
    height: 20px;
}

.nav-link:hover {
    background: #f1f5f9;
    color: #1e293b;
}

.nav-link.active {
    background: #fff7ed;
    color: #ea580c;
}

.sidebar-bottom {
    border-top: 1px solid #f1f5f9;
    padding-top: 16px;
}

.settings-btn {
    width: 100%;
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px 16px;
    background: none;
    border: none;
    color: #64748b;
    cursor: pointer;
    font-weight: 500;
}

.content-area {
    flex: 1;
    display: flex;
    flex-direction: column;
}

.top-nav {
    height: 70px;
    background: white;
    border-bottom: 1px solid #e2e8f0;
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0 32px;
}

.search-bar {
    display: flex;
    align-items: center;
    background: #f1f5f9;
    padding: 8px 16px;
    border-radius: 10px;
    width: 400px;
    gap: 10px;
}

.search-icon {
    width: 18px;
    height: 18px;
    color: #94a3b8;
}

.search-bar input {
    background: none;
    border: none;
    outline: none;
    flex: 1;
    font-size: 14px;
}

.user-actions {
    display: flex;
    align-items: center;
    gap: 20px;
}

.notif-badge {
    position: relative;
    color: #64748b;
    cursor: pointer;
}

.notif-badge svg {
    width: 22px;
    height: 22px;
}

.dot {
    position: absolute;
    top: 0;
    right: 0;
    width: 8px;
    height: 8px;
    background: #ef4444;
    border-radius: 50%;
    border: 2px solid white;
}

.divider {
    width: 1px;
    height: 24px;
    background: #e2e8f0;
}

.student-info {
    display: flex;
    align-items: center;
    gap: 10px;
    cursor: pointer;
}

.avatar-circle {
    width: 34px;
    height: 34px;
    background: #6366f1;
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 13px;
    font-weight: 700;
}

.user-name {
    font-size: 14px;
    font-weight: 600;
    color: #1e293b;
}

.page-container {
    padding: 32px;
    overflow-y: auto;
}
</style>