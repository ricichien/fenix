<script setup lang="ts">
import { ref, onMounted, computed } from 'vue'
import { getExams } from '@/services/examService'
import { getStudentStats } from '@/services/dashboardService'
import { useRouter } from 'vue-router'

type ExamListItem = {
    id: number
    title: string
    description?: string
    has_attempt?: boolean
}

const exams = ref<ExamListItem[]>([])
const router = useRouter()

const stats = ref({
    average_score: 0,
    pending_exams: 0,
    completed_exams: 0,
    highest_score: 0,
    lowest_score: 0,
})

const loading = ref(true)
const filter = ref<'all' | 'pending' | 'done'>('all')

const load = async () => {
    try {
        exams.value = await getExams()
        stats.value = await getStudentStats()
    } finally {
        loading.value = false
    }
}

const openExam = (id: number) => {
    router.push(`/student/exam/${id}`)
}

const filteredExams = computed(() => {
    if (filter.value === 'pending') {
        return exams.value.filter(e => !e.has_attempt)
    }
    if (filter.value === 'done') {
        return exams.value.filter(e => e.has_attempt)
    }
    return exams.value
})

const sortedExams = computed(() => {
    return [...filteredExams.value].sort((a, b) => {
        return Number(a.has_attempt) - Number(b.has_attempt)
    })
})

const progress = computed(() => {
    const total = exams.value.length
    const done = exams.value.filter(e => e.has_attempt).length
    return total ? Math.round((done / total) * 100) : 0
})

onMounted(load)
</script>

<template>
    <div v-if="loading" class="loading">
        Carregando dashboard...
    </div>

    <div v-else class="exam-dashboard">
        <div class="welcome-banner">
            <div class="banner-text">
                <h1>Olá, João!</h1>
                <p>
                    Você tem <strong>{{ stats.pending_exams }}</strong> provas pendentes.
                </p>
                <p>Progresso geral: <strong>{{ progress }}%</strong></p>
            </div>
            <div class="insight-card">
                <div class="score-circle">
                    <span class="score-num">{{ stats.average_score }}</span>
                    <span class="score-label">Média</span>
                </div>

            </div>
        </div>

        <div class="content-grid">
            <div class="main-col">
                <div class="section-header">
                    <h2>Avaliações</h2>

                    <div class="filter-tabs">
                        <button class="tab" :class="{ active: filter === 'all' }" @click="filter = 'all'">
                            Todas
                        </button>
                        <button class="tab" :class="{ active: filter === 'pending' }" @click="filter = 'pending'">
                            Pendentes
                        </button>
                        <button class="tab" :class="{ active: filter === 'done' }" @click="filter = 'done'">
                            Finalizadas
                        </button>
                    </div>
                </div>

                <div class="exam-cards-list">
                    <div v-for="exam in sortedExams" :key="exam.id" class="horizontal-exam-card">

                        <div class="subject-tag" :style="{ background: exam.has_attempt ? '#f0fdf4' : '#fff7ed' }">
                            <svg v-if="exam.has_attempt" viewBox="0 0 24 24" fill="none" stroke="#16a34a"
                                stroke-width="2.5">
                                <path d="M20 6L9 17l-5-5" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <svg v-else viewBox="0 0 24 24" fill="none" stroke="#ea580c" stroke-width="2.5">
                                <circle cx="12" cy="12" r="10" />
                                <path d="M12 6v6l4 2" />
                            </svg>
                        </div>

                        <div class="exam-details">
                            <h3>{{ exam.title }}</h3>
                            <div class="meta-row">
                                <span>{{ exam.description }}</span>
                            </div>
                        </div>

                        <div class="exam-status">
                            <span :class="['status-text', exam.has_attempt ? 'text-green' : 'text-orange']">
                                {{ exam.has_attempt ? 'Concluída' : 'Pendente' }}
                            </span>
                        </div>

                        <button @click="openExam(exam.id)" class="start-btn">
                            {{ exam.has_attempt ? 'Revisar' : 'Iniciar' }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
.exam-dashboard {
    display: flex;
    flex-direction: column;
    gap: 32px;
}

.welcome-banner {
    background: linear-gradient(135deg, #1e293b 0%, #334155 100%);
    padding: 40px;
    color: white;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.banner-text h1 {
    font-size: 28px;
    margin-bottom: 8px;
}

.banner-text p {
    color: #cbd5e1;
    font-size: 16px;
}

.view-schedule-btn {
    background: rgba(255, 255, 255, 0.1);
    border: 1px solid rgba(255, 255, 255, 0.2);
    color: white;
    padding: 12px 24px;
    border-radius: 12px;
    font-weight: 600;
    cursor: pointer;
    transition: 0.2s;
}

.view-schedule-btn:hover {
    background: rgba(255, 255, 255, 0.2);

}

.content-grid {
    display: grid;
    gap: 32px;
    padding-left: 20px;
    padding-right: 20px;

}

.section-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 24px;
}

.section-header h2 {
    font-size: 20px;
    color: #0f172a;
}

.filter-tabs {
    display: flex;
    gap: 8px;
    background: #e2e8f0;
    padding: 4px;
    border-radius: 8px;
}

.tab {
    border: none;
    background: none;
    padding: 6px 16px;
    border-radius: 6px;
    font-size: 13px;
    font-weight: 600;
    color: #64748b;
    cursor: pointer;
}

.tab.active {
    background: white;
    color: #0f172a;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
}

.exam-cards-list {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.horizontal-exam-card {
    background: white;
    border: 1px solid #e2e8f0;
    border-radius: 16px;
    padding: 16px 24px;
    display: flex;
    align-items: center;
    gap: 20px;
    transition: 0.2s;
}

.horizontal-exam-card:hover {
    border-color: #ea580c;
    transform: translateX(4px);
}

.subject-tag {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.subject-tag svg {
    width: 24px;
    height: 24px;
}

.exam-details {
    flex: 1;
}

.exam-details h3 {
    font-size: 16px;
    color: #0f172a;
    margin-bottom: 4px;
}

.meta-row {
    font-size: 13px;
    color: #94a3b8;
    display: flex;
    gap: 8px;
}

.status-text {
    font-size: 13px;
    font-weight: 700;
    text-transform: uppercase;
}

.text-green {
    color: #16a34a;
}

.text-orange {
    color: #ea580c;
}

.start-btn {
    padding: 10px 20px;
    border-radius: 8px;
    border: 1px solid #e2e8f0;
    background: white;
    color: #1e293b;
    font-weight: 600;
    cursor: pointer;
    transition: 0.2s;
}

.start-btn:hover:not(:disabled) {
    background: #ea580c;
    color: white;
    border-color: #ea580c;
}

/* Insight Sidebar */
.insight-card {
    background: rgb(255, 255, 255);
    border: 0px solid #ea580c;
    border-radius: 50%;
    /* padding: 24px; */
    text-align: center;
    margin-bottom: 24px;
    width: 100px;
    height: 100px;
}

.insight-card h3 {
    font-size: 16px;
    margin-bottom: 20px;
}

.score-circle {
    width: 100px;
    height: 100px;
    border: 6px solid #ffffff;
    border-top-color: #ea580c;
    border-radius: 50%;
    margin: 0 auto 16px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
}

.score-num {
    font-size: 24px;
    font-weight: 800;
    color: #0f172a;
}

.score-label {
    font-size: 10px;
    color: #94a3b8;
    text-transform: uppercase;
}

.insight-text {
    font-size: 13px;
    color: #64748b;
    line-height: 1.5;
}

.upcoming-classes h3 {
    font-size: 16px;
    margin-bottom: 16px;
}

.mini-class-card {
    background: white;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    padding: 12px;
    display: flex;
    gap: 12px;
}

.class-time {
    font-weight: 700;
    color: #ea580c;
    font-size: 13px;
}

.class-title {
    display: block;
    font-size: 14px;
    font-weight: 600;
}

.class-room {
    font-size: 12px;
    color: #94a3b8;
}
</style>
