<script setup lang="ts">
import { onMounted, ref, watch } from "vue";
import { getDashboardStats } from "@/services/dashboardService";

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
const currentPage = ref(1);
const itemsPerPage = ref(10);

const stats = ref<DashboardData>({
    total_attempts: 0,
    average_score: 0,
    average_percentage: 0,
    highest_score: 0,
    lowest_score: 0,
    ranking: [],
});

const loadDashboard = async () => {
    console.log("Iniciando busca de dados..."); // Log de depuração
    loading.value = true;
    error.value = null;

    try {
        const data = await getDashboardStats(currentPage.value, itemsPerPage.value);
        console.log("Dados recebidos:", data);
        stats.value = data;
    } catch (err: any) {
        console.error("Erro na API:", err);
        error.value = err?.response?.data?.message || "Erro ao carregar o dashboard.";
    } finally {
        loading.value = false;
    }
};

const nextPage = () => {
    if (stats.value.ranking.length === itemsPerPage.value) {
        currentPage.value++;
    }
};

const prevPage = () => {
    if (currentPage.value > 1) {
        currentPage.value--;
    }
};

watch(currentPage, () => {
    loadDashboard();
});

onMounted(() => {
    console.log("Componente Dashboard montado!");
    loadDashboard();
});
</script>

<template>
    <div class="dashboard">
        <h1>Dashboard do Professor</h1>

        <div v-if="loading && stats.total_attempts === 0">
            <p>Carregando métricas...</p>
        </div>

        <p v-else-if="error" class="error-box">{{ error }}</p>

        <div v-else>
            <div class="cards">
                <div class="card">
                    <span>Total de tentativas</span>
                    <strong>{{ stats.total_attempts }}</strong>
                </div>
                <div class="card highlight">
                    <span>Média da pontuação</span>
                    <strong>{{ stats.average_score }}</strong>
                </div>
                <div class="card">
                    <span>Média percentual</span>
                    <strong>{{ stats.average_percentage }}%</strong>
                </div>
                <div class="card success">
                    <span>Maior pontuação (Top 1)</span>
                    <strong>{{ stats.highest_score }}</strong>
                </div>
                <div class="card danger">
                    <span>Menor pontuação</span>
                    <strong>{{ stats.lowest_score }}</strong>
                </div>
            </div>

            <div class="ranking">
                <h2>Ranking de Alunos</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Posição</th>
                            <th>ID Aluno</th>
                            <th>Score</th>
                            <th>Percentual</th>
                        </tr>
                    </thead>
                    <tbody :class="{ 'loading-opacity': loading }">
                        <tr v-for="item in stats.ranking" :key="`${item.student_id}-${item.position}`">
                            <td>#{{ item.position }}</td>
                            <td>{{ item.student_id }}</td>
                            <td>{{ item.score }}</td>
                            <td>{{ item.percentage }}%</td>
                        </tr>
                        <tr v-if="stats.ranking.length === 0">
                            <td colspan="4">Nenhum dado encontrado.</td>
                        </tr>
                    </tbody>
                </table>

                <div class="pagination">
                    <button @click="prevPage" :disabled="currentPage === 1 || loading">
                        Anterior
                    </button>
                    <span>Página <strong>{{ currentPage }}</strong></span>
                    <button @click="nextPage" :disabled="stats.ranking.length < itemsPerPage.value || loading">
                        Próxima
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
.dashboard {
    padding: 20px;
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.cards {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
    gap: 16px;
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

.card.highlight {
    border-top: 4px solid #3498db;
}

.card.success {
    border-top: 4px solid #2ecc71;
}

.card.danger {
    border-top: 4px solid #e74c3c;
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
    padding: 12px;
    text-align: left;
}

.pagination {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 20px;
    margin-top: 20px;
}

.pagination button {
    padding: 8px 16px;
    cursor: pointer;
}

.loading-opacity {
    opacity: 0.5;
}

.error-box {
    color: #c62828;
    background: #feebee;
    padding: 15px;
    border-radius: 8px;
}
</style>