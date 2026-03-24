<script setup lang="ts">
import { ref, onMounted, computed } from 'vue'
import { useRoute } from 'vue-router'
import { getExamById, submitExam } from '@/services/examService'
import ExamResult from '@/components/exam/ExamResult.vue'

type ExamOption = {
    id: number
    text: string
}

type ExamQuestion = {
    id: number
    statement: string
    options: ExamOption[]
}

type ResultDetail = {
    question_id: number
    question_statement: string
    selected_option_id: number | null
    selected_text: string | null
    correct_option_id: number | null
    correct_text: string | null
    is_correct: boolean
}

type Result = {
    attempt_id: number
    exam_id: number
    student_id: number
    score: number
    percentage: number
    total_questions: number
    correct_count: number
    wrong_count: number
    details: ResultDetail[]
}

type Exam = {
    id: number
    title: string
    description: string
    questions: ExamQuestion[]
    has_attempt?: boolean
    last_result?: Result | null
}

const route = useRoute()

const exam = ref<Exam | null>(null)
const answers = ref<Record<number, number>>({})
const result = ref<Result | null>(null)
const loading = ref(false)
const errorMessage = ref('')

const load = async () => {
    exam.value = await getExamById(route.params.id)

    if (exam.value?.has_attempt && exam.value.last_result) {
        result.value = exam.value.last_result
    }
}

const submit = async () => {
    if (!confirm('Tem certeza que deseja enviar sua prova? Depois disso não será possível alterar as respostas.')) {
        return
    }

    loading.value = true
    errorMessage.value = ''

    try {
        const payloadAnswers = Object.entries(answers.value).map(([questionId, optionId]) => ({
            question_id: Number(questionId),
            selected_option_id: Number(optionId),
        }))

        const response = await submitExam(route.params.id, payloadAnswers)

        result.value = response.data
    } catch (error: any) {
        errorMessage.value =
            error?.response?.data?.message ||
            'Erro ao enviar a prova.'
    } finally {
        loading.value = false
    }
}

const correctPercentage = computed(() => {
    if (!result.value || result.value.total_questions === 0) return 0
    return Math.round((result.value.correct_count / result.value.total_questions) * 100)
})

const wrongPercentage = computed(() => {
    if (!result.value || result.value.total_questions === 0) return 0
    return 100 - correctPercentage.value
})

onMounted(load)
</script>

<template>
    <div class="forms-layout" v-if="exam">
        <header class="forms-header">
            <div class="header-content">
                <div class="title-section">
                    <h1>{{ exam.title }}</h1>
                    <p class="description">{{ exam.description || 'Responda com atenção todas as questões.' }}</p>
                </div>
            </div>
        </header>

        <main class="forms-body">
            <div v-if="result" class="content-container animate-in">
                <div class="result-summary-card">
                    <div class="score-circle-large">
                        <span class="score-val">{{ result.percentage }}%</span>
                        <span class="score-label">Pontuação</span>
                    </div>
                    <div class="stats-row">
                        <div class="stat">
                            <span class="label">Acertos</span>
                            <span class="val success">{{ result.correct_count }}</span>
                        </div>
                        <div class="stat">
                            <span class="label">Erros</span>
                            <span class="val danger">{{ result.wrong_count }}</span>
                        </div>
                    </div>
                </div>

                <ExamResult :resultDetails="result.details" />
            </div>

            <div v-else class="content-container animate-in">
                <div class="user-info-alert">
                    Olá, <strong>Aluno</strong>. Ao enviar este formulário, seu nome e endereço de e-mail serão
                    registrados.
                    <span class="required-notice">* Obrigatória</span>
                </div>

                <div v-for="(q, index) in exam.questions" :key="q.id" class="q-card">
                    <p class="q-statement">
                        <span class="q-number">{{ index + 1 }}.</span>
                        {{ q.statement }}
                        <span class="required-star">*</span>
                    </p>

                    <div class="options-list">
                        <label v-for="opt in q.options" :key="opt.id" class="opt-item">
                            <input type="radio" :name="'q-' + q.id" :value="opt.id" v-model="answers[q.id]"
                                class="forms-radio" />
                            <span class="opt-text">{{ opt.text }}</span>
                        </label>
                    </div>
                </div>

                <div class="forms-footer">
                    <p v-if="errorMessage" class="error-msg">{{ errorMessage }}</p>
                    <button @click="submit" :disabled="loading" class="btn-submit-forms">
                        {{ loading ? 'Enviando...' : 'Enviar' }}
                    </button>
                    <div class="footer-note">Nunca forneça sua senha. Reportar abuso</div>
                </div>
            </div>
        </main>
    </div>
</template>

<style scoped>
.forms-layout {
    min-height: 100vh;
    background-color: #f3f2f1;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

.forms-header {
    background-color: #ea580c;
    /* Seu laranja */
    color: white;
    padding: 40px 20px 80px 20px;
    display: flex;
    justify-content: center;
}

.header-content {
    max-width: 800px;
    width: 100%;
    position: relative;
}

.btn-back {
    background: rgba(255, 255, 255, 0.1);
    border: 1px solid rgba(255, 255, 255, 0.3);
    color: white;
    padding: 6px 16px;
    border-radius: 4px;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 14px;
    margin-bottom: 24px;
}

.title-section h1 {
    font-size: 28px;
    font-weight: 600;
    margin: 0 0 12px 0;
}

.title-section {
    text-align: center;
    width: 100%;
}

.description {
    opacity: 0.9;
    font-size: 15px;
    margin: 0 auto;
    max-width: 600px;
}

.forms-body {
    margin-top: -50px;
    padding: 0 20px 60px 20px;
    display: flex;
    justify-content: center;
}

.content-container {
    max-width: 800px;
    width: 100%;
}

.user-info-alert {
    background: white;
    padding: 24px;
    font-size: 14px;
    color: #323130;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.required-notice {
    display: block;
    margin-top: 16px;
    color: #d83b01;
    font-weight: 600;
}

.q-card {
    background: white;
    padding: 10px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
}

.q-statement {
    font-size: 16px;
    font-weight: 600;
    color: #323130;
    margin-bottom: 24px;
    margin-left: 24px;
}

.q-number {
    margin-right: 8px;
}

.required-star {
    color: #d83b01;
    margin-left: 4px;
}

/* Opções */
.options-list {
    display: flex;
    flex-direction: column;
    gap: 16px;
    margin-left: 24px;
    margin-bottom: 20px;
}

.opt-item {
    display: flex;
    align-items: center;
    cursor: pointer;
    padding: 4px 0;
}

.forms-radio {
    appearance: none;
    width: 20px;
    height: 20px;
    border: 1px solid #605e5c;
    border-radius: 50%;
    margin-right: 12px;
    display: grid;
    place-content: center;
    cursor: pointer;
}

.forms-radio::before {
    content: "";
    width: 10px;
    height: 10px;
    border-radius: 50%;
    transform: scale(0);
    transition: 120ms transform ease-in-out;
    background-color: #ea580c;
}

.forms-radio:checked {
    border-color: #ea580c;
}

.forms-radio:checked::before {
    transform: scale(1);
}

.opt-text {
    font-size: 15px;
    color: #323130;
}

/* Rodapé e Botão */
.forms-footer {
    margin-top: 32px;
}

.btn-submit-forms {
    background-color: #ea580c;
    color: white;
    border: none;
    padding: 10px 32px;
    font-size: 16px;
    font-weight: 600;
    border-radius: 2px;
    cursor: pointer;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
}

.btn-submit-forms:hover {
    background-color: #c2410c;
}

.footer-note {
    margin-top: 40px;
    font-size: 12px;
    color: #605e5c;
    text-align: center;
}

.result-summary-card {
    background: white;
    padding: 40px;
    border-radius: 4px;
    text-align: center;
    margin-bottom: 24px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

.score-circle-large {
    display: flex;
    flex-direction: column;
    align-items: center;
    margin-bottom: 24px;
}

.score-val {
    font-size: 48px;
    font-weight: 800;
    color: #ea580c;
}

.stats-row {
    display: flex;
    justify-content: center;
    gap: 40px;
}

.stat .label {
    font-size: 14px;
    color: #605e5c;
    display: block;
}

.stat .val {
    font-size: 24px;
    font-weight: 700;
}

.success {
    color: #107c10;
}

.danger {
    color: #a4262c;
}

.animate-in {
    animation: slideUp 0.5s ease-out;
}

@keyframes slideUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }

    to {
        opacity: 1;
        transform: translateY(0);
    }
}
</style>