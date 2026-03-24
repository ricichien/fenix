<script setup lang="ts">
import { useRouter } from 'vue-router'
import { setCurrentUser } from '@/stores/user'
import Navbar from '@/components/navbar/Navbar.vue'

const router = useRouter()

const enterAsProfessor = () => {
    setCurrentUser({ id: 1, role: 'professor' })
    router.push('/professor')
}

const enterAsStudent = () => {
    setCurrentUser({ id: 2, role: 'student' })
    router.push('/student')
}
</script>

<template>
    <Navbar />
    <div class="bento-layout">
        <main class="bento-grid">

            <div class="bento-card hero-card animate-in" style="--delay: 0.1s">
                <div class="hero-content">
                    <div class="logo">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                            <path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        Fênix<span>Edu</span>
                    </div>
                    <h1>O seu futuro<br />começa aqui.</h1>
                    <p>Plataforma inteligente de avaliações e aprendizado contínuo.</p>
                </div>
                <div class="abstract-shape"></div>
            </div>

            <button class="bento-card action-card student-card animate-in" style="--delay: 0.2s"
                @click="enterAsStudent">
                <div class="card-top">
                    <div class="icon-box">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M12 14l9-5-9-5-9 5 9 5z" />
                            <path
                                d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
                        </svg>
                    </div>
                    <div class="action-arrow">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                            <path d="M5 12h14M12 5l7 7-7 7" />
                        </svg>
                    </div>
                </div>
                <div class="card-bottom">
                    <h2>Sou Aluno</h2>
                    <p>Acessar minhas provas e resultados</p>
                </div>
            </button>

            <button class="bento-card action-card professor-card animate-in" style="--delay: 0.3s"
                @click="enterAsProfessor">
                <div class="card-top">
                    <div class="icon-box">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path
                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5"
                                stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </div>
                    <div class="action-arrow">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                            <path d="M5 12h14M12 5l7 7-7 7" />
                        </svg>
                    </div>
                </div>
                <div class="card-bottom">
                    <h2>Sou Professor</h2>
                    <p>Gerenciar turmas e criar avaliações</p>
                </div>
            </button>

            <!-- <div class="bento-card info-card animate-in" style="--delay: 0.4s">
                <div class="info-content">
                    <h3>Precisa de ajuda?</h3>
                    <p>Acesse nossa central de suporte para alunos e docentes.</p>
                </div>
                <button class="support-btn">Falar com Suporte</button>
            </div> -->

        </main>
    </div>
</template>

<style scoped>
* {
    box-sizing: border-box;
}

.bento-layout {
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: #ea580c;
    padding: 24px;
}

/* .bento-layout {
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 24px;
    background:
        linear-gradient(rgba(234, 88, 12, 0.85), rgba(234, 88, 12, 0.85)),
        url('/images/bg.jpg') center/cover no-repeat;
} */

.bento-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    grid-template-rows: repeat(2, minmax(220px, auto));
    gap: 24px;
    max-width: 1100px;
    width: 100%;
}

.bento-card {
    background: #ffffff;
    border-radius: 10px;
    padding: 32px;
    position: relative;
    overflow: hidden;
    display: flex;
    flex-direction: column;
}

.hero-card {
    grid-column: span 2;
    grid-row: span 2;
    /* O gradiente vem antes da URL para ficar por cima */
    background: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)),
        url('@/images/bg.jpg') center/cover no-repeat;
    color: white;
    justify-content: center;
    border: none;
}

.hero-content {
    position: relative;
    z-index: 2;
    max-width: 400px;
}

.logo {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 20px;
    font-weight: 800;
    margin-bottom: 40px;
    letter-spacing: -0.5px;
}

.logo svg {
    width: 28px;
    height: 28px;
}

.logo span {
    opacity: 0.7;
    font-weight: 500;
}

.hero-card h1 {
    font-size: clamp(36px, 4vw, 56px);
    font-weight: 800;
    line-height: 1.05;
    letter-spacing: -2px;
    margin: 0 0 16px 0;
}

.hero-card p {
    font-size: 18px;
    color: rgba(255, 255, 255, 0.85);
    line-height: 1.5;
    margin: 0;
}

.abstract-shape {
    position: absolute;
    bottom: -10%;
    right: -5%;
    width: 400px;
    height: 400px;
    border-radius: 50%;
    pointer-events: none;
}

.action-card {
    cursor: pointer;
    border: 2px solid transparent;
    text-align: left;
    transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
}

.action-card:hover {
    transform: scale(0.97);
    border-color: rgba(234, 88, 12, 0.2);
    box-shadow: 0 12px 30px rgba(234, 88, 12, 0.08);
}

.action-card:active {
    transform: scale(0.94);
}

.card-top {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: auto;
}

.icon-box {
    width: 64px;
    height: 64px;
    border-radius: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #f8fafc;
    color: #0f172a;
    transition: all 0.3s ease;
}

.action-card:hover .icon-box {
    background: #ea580c;
    color: white;
}

.icon-box svg {
    width: 32px;
    height: 32px;
}

.action-arrow {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: #f1f5f9;
    color: #94a3b8;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
}

.action-arrow svg {
    width: 20px;
    transition: transform 0.3s ease;
}

.action-card:hover .action-arrow {
    background: #fff7ed;
    color: #ea580c;
}

.action-card:hover .action-arrow svg {
    transform: translateX(4px) scale(1.1);
}

.card-bottom {
    margin-top: 40px;
}

.card-bottom h2 {
    font-size: 24px;
    font-weight: 700;
    color: #0f172a;
    margin: 0 0 8px 0;
    letter-spacing: -0.5px;
}

.card-bottom p {
    font-size: 15px;
    color: #64748b;
    margin: 0;
    line-height: 1.4;
}

.info-card {
    background: #1e293b;
    color: white;
    justify-content: space-between;
}

.info-content h3 {
    font-size: 20px;
    margin: 0 0 8px 0;
}

.info-content p {
    font-size: 14px;
    color: #94a3b8;
    margin: 0;
    line-height: 1.5;
}

.support-btn {
    align-self: flex-start;
    margin-top: 24px;
    padding: 10px 20px;
    background: rgba(255, 255, 255, 0.1);
    color: white;
    border: none;
    border-radius: 12px;
    font-weight: 600;
    cursor: pointer;
    transition: 0.2s;
}

.support-btn:hover {
    background: rgba(255, 255, 255, 0.2);
}

.animate-in {
    opacity: 0;
    transform: translateY(20px) scale(0.98);
    animation: pop-in 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    animation-delay: var(--delay);
}

@keyframes pop-in {
    to {
        opacity: 1;
        transform: translateY(0) scale(1);
    }
}

@media (max-width: 900px) {
    .bento-grid {
        grid-template-columns: 1fr 1fr;
    }

    .hero-card {
        grid-column: span 2;
        grid-row: span 1;
        padding: 40px 32px;
    }

    .info-card {
        grid-column: span 2;
        flex-direction: row;
        align-items: center;
    }

    .support-btn {
        margin-top: 0;
    }
}

@media (max-width: 600px) {
    .bento-grid {
        grid-template-columns: 1fr;
        grid-template-rows: auto;
    }

    .hero-card,
    .info-card {
        grid-column: span 1;
    }

    .info-card {
        flex-direction: column;
        align-items: flex-start;
    }

    .support-btn {
        margin-top: 20px;
    }
}
</style>