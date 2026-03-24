<script setup lang="ts">
import { RouterLink, RouterView } from "vue-router";
</script>

<template>
    <div class="app-shell">
        <aside class="sidebar animate-in-left">
            <div class="sidebar-top">
                <div class="brand-section">
                    <div class="logo-stack">
                        <div class="logo-bg">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
                                <path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z" stroke-linecap="round"
                                    stroke-linejoin="round" />
                            </svg>
                        </div>
                        <span class="logo-text">Fênix<span>Edu</span></span>
                    </div>
                </div>

                <nav class="nav-grid">
                    <div class="nav-group">
                        <p class="group-label">Plataforma</p>

                        <RouterLink to="/professor" class="nav-link stagger-1" exact-active-class="active"">
                            <div class=" link-inner">
                            <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2">
                                <rect x="3" y="3" width="7" height="7" rx="1.5" />
                                <rect x="14" y="3" width="7" height="7" rx="1.5" />
                                <rect x="14" y="14" width="7" height="7" rx="1.5" />
                                <rect x="3" y="14" width="7" height="7" rx="1.5" />
                            </svg>
                            <span>Dashboard</span>
                    </div>
                    <div class="active-indicator"></div>
                    </RouterLink>

                    <RouterLink to="/professor/exams" class="nav-link stagger-2" active-class="active">
                        <div class="link-inner">
                            <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2">
                                <path
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                                    stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <span>Gerenciar Provas</span>
                        </div>
                        <div class="active-indicator"></div>
                    </RouterLink>
            </div>

            <div class="nav-group">
                <p class="group-label">Ações Rápidas</p>
                <RouterLink to="/professor/exams/new" class="action-card stagger-3">
                    <div class="action-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
                            <path d="M12 5v14M5 12h14" stroke-linecap="round" />
                        </svg>
                    </div>
                    <span>Criar Avaliação</span>
                </RouterLink>
            </div>
            </nav>
    </div>

    <div class="sidebar-bottom animate-in-up">
        <div class="user-card">
            <div class="user-avatar">
                <img src="https://ui-avatars.com/api/?name=Anderson+Silva&background=ea580c&color=fff" alt="Avatar">
                <div class="online-dot"></div>
            </div>
            <div class="user-meta">
                <span class="u-name">Prof. Anderson</span>
                <span class="u-role">Administrador</span>
            </div>
        </div>
    </div>
    </aside>

    <main class="main-content">
        <header class="header-bar animate-in-top">
            <div class="header-left">
                <nav class="breadcrumb-nav">
                    <span class="b-root">Administração</span>
                    <svg class="b-sep" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path d="M9 18l6-6-6-6" />
                    </svg>
                    <span class="b-current">Painel Geral</span>
                </nav>
            </div>

            <div class="header-right">
                <div class="search-trigger">
                    <kbd>⌘</kbd><kbd>K</kbd>
                </div>
                <button class="notif-btn">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M18 8a6 6 0 00-12 0c0 7-3 9-3 9h18s-3-2-3-9m-5 13a2 2 0 01-4 0" />
                    </svg>
                    <span class="notif-badge"></span>
                </button>
                <div class="v-sep"></div>
                <button class="exit-btn" @click="$router.push('/')">Sair</button>
            </div>
        </header>

        <section class="page-view">
            <RouterView v-slot="{ Component }">
                <transition name="page-fade" mode="out-in">
                    <component :is="Component" />
                </transition>
            </RouterView>
        </section>
    </main>
    </div>
</template>

<style scoped>
@keyframes slideFromLeft {
    from {
        transform: translateX(-30px);
        opacity: 0;
    }

    to {
        transform: translateX(0);
        opacity: 1;
    }
}

@keyframes slideFromTop {
    from {
        transform: translateY(-20px);
        opacity: 0;
    }

    to {
        transform: translateY(0);
        opacity: 1;
    }
}

@keyframes slideFromBottom {
    from {
        transform: translateY(20px);
        opacity: 0;
    }

    to {
        transform: translateY(0);
        opacity: 1;
    }
}

.animate-in-left {
    animation: slideFromLeft 0.8s cubic-bezier(0.16, 1, 0.3, 1) both;
}

.animate-in-top {
    animation: slideFromTop 0.8s 0.2s cubic-bezier(0.16, 1, 0.3, 1) both;
}

.animate-in-up {
    animation: slideFromBottom 0.6s 0.6s both;
}

.stagger-1 {
    animation: slideFromLeft 0.6s 0.3s both;
}

.stagger-2 {
    animation: slideFromLeft 0.6s 0.4s both;
}

.stagger-3 {
    animation: slideFromLeft 0.6s 0.5s both;
}

.page-fade-enter-active,
.page-fade-leave-active {
    transition: all 0.3s ease;
}

.page-fade-enter-from {
    opacity: 0;
    transform: translateY(8px);
}

.page-fade-leave-to {
    opacity: 0;
    transform: translateY(-8px);
}

.app-shell {
    display: flex;
    height: 100vh;
    background-color: #fdfdfd;
    color: #1a1a1e;
    overflow: hidden;
}

.sidebar {
    width: 280px;
    background: #ffffff;
    border-right: 1px solid rgba(0, 0, 0, 0.06);
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    z-index: 10;
}

.brand-section {
    padding: 32px 24px;
}

.logo-stack {
    display: flex;
    align-items: center;
    gap: 12px;
}

.logo-bg {
    background: linear-gradient(135deg, #ea580c, #f97316);
    color: white;
    width: 32px;
    height: 32px;
    border-radius: 8px;
    padding: 6px;
    box-shadow: 0 4px 12px rgba(234, 88, 12, 0.3);
}

.logo-text {
    font-size: 19px;
    font-weight: 800;
    letter-spacing: -0.8px;
}

.logo-text span {
    color: #ea580c;
    font-weight: 500;
}

.nav-grid {
    padding: 0 16px;
}

.group-label {
    font-size: 10px;
    font-weight: 700;
    color: #a1a1a6;
    text-transform: uppercase;
    letter-spacing: 1px;
    padding: 0 12px;
    margin-bottom: 12px;
}

.nav-group {
    margin-bottom: 32px;
}

.nav-link {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 10px 12px;
    color: #636366;
    text-decoration: none;
    border-radius: 10px;
    font-weight: 500;
    font-size: 14px;
    margin-bottom: 4px;
    transition: all 0.2s ease;
}

.link-inner {
    display: flex;
    align-items: center;
    gap: 12px;
}

.nav-icon {
    width: 18px;
    height: 18px;
}

.nav-link:hover {
    background: #f5f5f7;
    color: #1d1d1f;
}

.nav-link.active {
    background: #fff7ed;
    color: #ea580c;
}

.active-indicator {
    width: 4px;
    height: 4px;
    background: transparent;
    border-radius: 50%;
}

.nav-link.active .active-indicator {
    background: #ea580c;
}

.action-card {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px;
    background: #1c1c1e;
    color: white;
    text-decoration: none;
    border-radius: 12px;
    font-size: 14px;
    font-weight: 600;
    transition: 0.3s cubic-bezier(0.2, 0.8, 0.2, 1);
}

.action-card:hover {
    transform: translateY(-2px);
    background: #ea580c;
    box-shadow: 0 8px 20px rgba(234, 88, 12, 0.2);
}

.action-icon {
    width: 24px;
    height: 24px;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 6px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.action-icon svg {
    width: 14px;
}

.header-bar {
    height: 72px;
    padding: 0 32px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    background: rgba(255, 255, 255, 0.8);
    backdrop-filter: blur(10px);
    border-bottom: 1px solid rgba(0, 0, 0, 0.05);
}

.breadcrumb-nav {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 13px;
}

.b-root {
    color: #86868b;
}

.b-sep {
    width: 12px;
    color: #d2d2d7;
}

.b-current {
    color: #1d1d1f;
    font-weight: 600;
}

.header-right {
    display: flex;
    align-items: center;
    gap: 20px;
}

.search-trigger {
    background: #f5f5f7;
    padding: 6px 12px;
    border-radius: 8px;
    display: flex;
    gap: 4px;
    color: #86868b;
    font-size: 12px;
    cursor: pointer;
}

.notif-btn {
    position: relative;
    background: none;
    border: none;
    color: #86868b;
    cursor: pointer;
}

.notif-badge {
    position: absolute;
    top: -2px;
    right: -2px;
    width: 6px;
    height: 6px;
    background: #ea580c;
    border-radius: 50%;
    border: 2px solid white;
}

.exit-btn {
    background: transparent;
    border: 1px solid #e5e5e7;
    padding: 8px 16px;
    border-radius: 8px;
    font-size: 13px;
    font-weight: 600;
    color: #1d1d1f;
    cursor: pointer;
    transition: 0.2s;
}

.exit-btn:hover {
    background: #fef2f2;
    border-color: #fecaca;
    color: #ef4444;
}

.sidebar-bottom {
    padding: 24px;
    border-top: 1px solid rgba(0, 0, 0, 0.04);
}

.user-card {
    display: flex;
    align-items: center;
    gap: 12px;
}

.user-avatar {
    position: relative;
    width: 38px;
    height: 38px;
}

.user-avatar img {
    width: 100%;
    border-radius: 50%;
}

.online-dot {
    position: absolute;
    bottom: 0;
    right: 0;
    width: 10px;
    height: 10px;
    background: #34c759;
    border: 2px solid white;
    border-radius: 50%;
}

.u-name {
    display: block;
    font-size: 14px;
    font-weight: 700;
    color: #1d1d1f;
}

.u-role {
    font-size: 11px;
    color: #86868b;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.main-content {
    flex: 1;
    display: flex;
    flex-direction: column;
    overflow-y: auto;
}

.page-view {
    padding: 32px;
    max-width: 1400px;
    margin: 0 auto;
    width: 100%;
}

.v-sep {
    width: 1px;
    height: 20px;
    background: #e5e5e7;
}
</style>