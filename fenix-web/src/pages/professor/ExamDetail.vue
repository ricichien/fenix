<script setup lang="ts">
import { ref, onMounted } from "vue";
import { useRoute, useRouter } from "vue-router";
import ExamQuestionCard from "@/components/professor/exam/ExamQuestionCard.vue";
import type { ExamPayload } from "@/components/professor/exam/types";
import { getExamWithAnswers } from "@/services/examService";

const route = useRoute();
const router = useRouter();

const exam = ref<ExamPayload | null>(null);
const loading = ref(true);

const loadExam = async () => {
    try {
        exam.value = await getExamWithAnswers(route.params.id as string);
    } finally {
        loading.value = false;
    }
};

onMounted(loadExam);
</script>

<template>
    <div v-if="loading" class="state-box">Carregando detalhes...</div>

    <div v-else-if="exam" class="detail-layout">
        <header class="detail-header">
            <div class="header-inner">
                <h1>{{ exam.title }}</h1>
                <p>{{ exam.description || "Sem descrição informada." }}</p>
            </div>
        </header>

        <main class="detail-body">
            <div class="content-container">
                <ExamQuestionCard v-for="(question, index) in exam.questions" :key="question.id ?? index"
                    :question="question" :index="index" readonly />

                <div class="detail-footer">
                    <button type="button" class="btn-edit-float"
                        @click="router.push(`/professor/exams/${route.params.id}/edit`)">
                        Editar
                    </button>
                </div>
            </div>
        </main>
    </div>
</template>

<style scoped>
.detail-layout {
    background: #f3f2f1;
    min-height: 100vh;
}

.state-box {
    padding: 40px 20px;
    text-align: center;
    color: #666;
}

.detail-header {
    background: #323130;
    color: white;
    padding: 40px 20px 80px;
    text-align: center;
}

.header-inner {
    max-width: 800px;
    margin: 0 auto;
    position: relative;
}

.back-btn {
    position: absolute;
    left: 0;
    top: 0;
    background: transparent;
    border: 1px solid #ffffff44;
    color: white;
    cursor: pointer;
    padding: 6px 12px;
    border-radius: 8px;
}

.detail-header h1 {
    margin: 0;
    font-size: 28px;
}

.detail-header p {
    margin: 8px 0 0;
    color: rgba(255, 255, 255, 0.85);
}

.detail-body {
    margin-top: -50px;
    padding: 0 20px 60px;
    display: flex;
    justify-content: center;
}

.content-container {
    max-width: 800px;
    width: 100%;
}

.detail-footer {
    margin-top: 20px;
}

.btn-edit-float {
    width: 100%;
    background: #ea580c;
    color: white;
    border: none;
    padding: 16px;
    font-size: 16px;
    font-weight: 700;
    cursor: pointer;
}

.btn-edit-float:hover {
    background: #c2410c;
}
</style>