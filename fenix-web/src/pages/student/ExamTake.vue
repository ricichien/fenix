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
    <div v-if="exam">
        <h1>{{ exam.title }}</h1>
        <p>{{ exam.description }}</p>

        <div v-if="result">
            <h2>Resultado</h2>

            <div style="display: flex; gap: 12px; flex-wrap: wrap; margin-bottom: 20px;">
                <div><strong>Pontuação:</strong> {{ result.score }}</div>
                <div><strong>Percentual:</strong> {{ result.percentage }}%</div>
                <div><strong>Acertos:</strong> {{ result.correct_count }}</div>
                <div><strong>Erros:</strong> {{ result.wrong_count }}</div>
            </div>

            <div style="margin-bottom: 20px;">
                <h3>Gráfico</h3>

                <div style="display: grid; gap: 8px; max-width: 400px;">
                    <div>
                        <div>Acertos ({{ correctPercentage }}%)</div>
                        <div style="background: #ddd; height: 16px; border-radius: 8px; overflow: hidden;">
                            <div :style="{ width: correctPercentage + '%', background: '#22c55e', height: '100%' }">
                            </div>
                        </div>
                    </div>

                    <div>
                        <div>Erros ({{ wrongPercentage }}%)</div>
                        <div style="background: #ddd; height: 16px; border-radius: 8px; overflow: hidden;">
                            <div :style="{ width: wrongPercentage + '%', background: '#ef4444', height: '100%' }"></div>
                        </div>
                    </div>
                </div>
            </div>

            <ExamResult :resultDetails="result.details" />
        </div>

        <div v-else>
            <div v-for="q in exam.questions" :key="q.id" style="margin-bottom: 20px;">
                <p><strong>{{ q.statement }}</strong></p>

                <div v-for="opt in q.options" :key="opt.id">
                    <label>
                        <input type="radio" :name="'q-' + q.id" :value="opt.id" v-model="answers[q.id]" />
                        {{ opt.text }}
                    </label>
                </div>
            </div>

            <p v-if="errorMessage" style="color: red;">{{ errorMessage }}</p>

            <button @click="submit" :disabled="loading">
                {{ loading ? 'Enviando...' : 'Enviar' }}
            </button>
        </div>
    </div>
</template>