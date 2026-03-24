<script setup lang="ts">
import { onMounted, ref } from "vue";
import { useRouter } from "vue-router";
import { getExams, deleteExam } from "@/services/examService";

type Exam = {
    id: number;
    title: string;
    description?: string | null;
};

const router = useRouter();
const exams = ref<Exam[]>([]);
const loading = ref(false);
const removingId = ref<number | null>(null);

const loadExams = async () => {
    loading.value = true;
    try {
        exams.value = await getExams();
    } finally {
        loading.value = false;
    }
};

const removeExam = async (id: number) => {
    const confirmed = window.confirm("Deseja excluir esta prova permanentemente?");
    if (!confirmed) return;

    removingId.value = id;
    try {
        await deleteExam(id);
        await loadExams();
    } finally {
        removingId.value = null;
    }
};

onMounted(loadExams);
</script>

<template>
    <div class="page">
        <div class="page-header">
            <div>
                <h1>Banco de Provas</h1>
                <p>Gerencie as avaliações da plataforma</p>
            </div>

            <!-- <button class="primary-btn" type="button" @click="router.push('/professor/exams/new')">
                Nova prova
            </button> -->
        </div>

        <div v-if="loading" class="empty-state">
            Carregando avaliações...
        </div>

        <div v-else-if="exams.length === 0" class="empty-state">
            Nenhuma prova encontrada. Comece criando uma.
        </div>

        <div v-else class="exam-list">
            <div v-for="exam in exams" :key="exam.id" class="exam-item">
                <div class="exam-info">
                    <h2>{{ exam.title }}</h2>
                    <p>{{ exam.description || "Sem descrição" }}</p>
                </div>

                <div class="exam-actions">
                    <button class="action-btn" type="button" @click="router.push(`/professor/exams/${exam.id}`)">
                        Ver
                    </button>

                    <button class="action-btn" type="button" @click="router.push(`/professor/exams/${exam.id}/edit`)">
                        Editar
                    </button>

                    <button class="action-btn danger" type="button" :disabled="removingId === exam.id"
                        @click="removeExam(exam.id)">
                        {{ removingId === exam.id ? "Excluindo..." : "Excluir" }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
.page {
    max-width: 1100px;
    margin: 0 auto;
    padding: 40px 32px;
}

.page-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 16px;
    margin-bottom: 28px;
}

.page-header h1 {
    margin: 0;
    font-size: 28px;
    font-weight: 700;
    color: #2f2f2f;
}

.page-header p {
    margin: 6px 0 0;
    color: #6b7280;
}

.primary-btn {
    border: none;
    background: #f97316;
    color: #fff;
    padding: 10px 16px;
    border-radius: 8px;
    font-weight: 600;
    cursor: pointer;
}

.primary-btn:hover {
    background: #ea580c;
}

.exam-list {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.exam-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 20px;
    padding: 18px 20px;
    background: #fff;
    border: 1px solid #ececec;
    border-radius: 12px;
}

.exam-item:hover {
    border-color: #dedede;
}

.exam-info {
    min-width: 0;
}

.exam-info h2 {
    margin: 0;
    font-size: 18px;
    font-weight: 600;
    color: #222;
}

.exam-info p {
    margin: 6px 0 0;
    color: #6b7280;
    font-size: 14px;
}

.exam-actions {
    display: flex;
    gap: 8px;
    flex-shrink: 0;
}

.action-btn {
    border: 1px solid #d1d5db;
    background: #fff;
    color: #374151;
    padding: 8px 14px;
    border-radius: 8px;
    cursor: pointer;
    transition: 0.2s;
    font-size: 14px;
}

.action-btn:hover {
    background: #f9fafb;
}

.action-btn.danger {
    border-color: #fecaca;
    color: #b91c1c;
}

.action-btn.danger:hover {
    background: #fef2f2;
}

.action-btn:disabled {
    opacity: 0.7;
    cursor: not-allowed;
}

.empty-state {
    padding: 28px;
    border: 1px dashed #d1d5db;
    border-radius: 12px;
    color: #6b7280;
    background: #fff;
}

@media (max-width: 720px) {

    .page-header,
    .exam-item {
        flex-direction: column;
        align-items: stretch;
    }

    .exam-actions {
        justify-content: flex-start;
        flex-wrap: wrap;
    }

    .primary-btn {
        width: 100%;
    }
}
</style>