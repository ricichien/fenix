<script setup lang="ts">
import { onMounted, ref } from "vue";
import api from "@/services/api";

type RankingItem = {
    position: number;
    student_id: number;
    score: number;
    percentage: number;
};

type DashboardData = {
    total_attempts: number;
    average_score: number;
    average_percentage: number;
    highest_score: number;
    lowest_score: number;
    ranking: RankingItem[];
};

const loading = ref(false);
const error = ref<string | null>(null);

const stats = ref<DashboardData>({
    total_attempts: 0,
    average_score: 0,
    average_percentage: 0,
    highest_score: 0,
    lowest_score: 0,
    ranking: [],
});

const loadDashboard = async () => {
    loading.value = true;
    error.value = null;

    try {
        const { data } = await api.get("/dashboard");
        stats.value = data;
    } catch (err: any) {
        error.value =
            err?.response?.data?.message ||
            "Erro ao carregar o dashboard.";
    } finally {
        loading.value = false;
    }
};

onMounted(loadDashboard);
</script>

<template>
    <div class="dashboard">
        <h1>Dashboard do Professor</h1>

        <p v-if="loading">Carregando métricas...</p>
        <p v-else-if="error" class="error">{{ error }}</p>

        <div v-else>
            <div class="cards">
                <div class="card">
                    <span>Total de tentativas</span>
                    <strong>{{ stats.total_attempts }}</strong>
                </div>

                <div class="card">
                    <span>Média da pontuação</span>
                    <strong>{{ stats.average_score }}</strong>
                </div>

                <div class="card">
                    <span>Média percentual</span>
                    <strong>{{ stats.average_percentage }}%</strong>
                </div>

                <div class="card">
                    <span>Maior pontuação</span>
                    <strong>{{ stats.highest_score }}</strong>
                </div>

                <div class="card">
                    <span>Menor pontuação</span>
                    <strong>{{ stats.lowest_score }}</strong>
                </div>
            </div>

            <div class="ranking">
                <h2>Ranking</h2>

                <table>
                    <thead>
                        <tr>
                            <th>Posição</th>
                            <th>Aluno</th>
                            <th>Score</th>
                            <th>Percentual</th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr v-for="item in stats.ranking" :key="`${item.student_id}-${item.position}`">
                            <td>{{ item.position }}</td>
                            <td>{{ item.student_id }}</td>
                            <td>{{ item.score }}</td>
                            <td>{{ item.percentage }}%</td>
                        </tr>

                        <tr v-if="stats.ranking.length === 0">
                            <td colspan="4">Nenhuma tentativa encontrada.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>

<style scoped>
.dashboard {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.cards {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
    gap: 16px;
    margin-bottom: 24px;
}

.card {
    border: 1px solid #ddd;
    border-radius: 12px;
    padding: 16px;
    background: #fff;
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.card span {
    font-size: 14px;
    color: #666;
}

.card strong {
    font-size: 24px;
}

.ranking table {
    width: 100%;
    border-collapse: collapse;
}

.ranking th,
.ranking td {
    border: 1px solid #ddd;
    padding: 10px;
    text-align: left;
}

.error {
    color: #c62828;
}
</style>