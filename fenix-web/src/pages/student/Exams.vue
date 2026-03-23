<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { getExams } from '@/services/examService'
import { useRouter } from 'vue-router'

type ExamListItem = {
    id: number
    title: string
    has_attempt?: boolean
}

const exams = ref<ExamListItem[]>([])
const router = useRouter()

const load = async () => {
    exams.value = await getExams()
}

const openExam = (id: number) => {
    router.push(`/student/exam/${id}`)
}

onMounted(load)
</script>

<template>
    <div>
        <h1>Provas disponíveis</h1>

        <ul>
            <li v-for="exam in exams" :key="exam.id" style="margin-bottom: 12px;">
                <span>{{ exam.title }}</span>
                <button @click="openExam(exam.id)" style="margin-left: 12px;">
                    {{ exam.has_attempt ? 'Resultado' : 'Iniciar' }}
                </button>
            </li>
        </ul>
    </div>
</template>