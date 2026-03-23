<script setup lang="ts">
import { ref, onMounted } from "vue"
import { useRoute } from "vue-router"
import { getExamWithAnswers } from "@/services/examService"

const route = useRoute()

const exam = ref<any>(null)

const loadExam = async () => {
    exam.value = await getExamWithAnswers(route.params.id)
}

onMounted(loadExam)
</script>

<template>
    <div v-if="exam">
        <h1>{{ exam.title }}</h1>
        <p>{{ exam.description }}</p>

        <div v-for="question in exam.questions" :key="question.id">
            <h3>{{ question.statement }}</h3>

            <ul>
                <li v-for="option in question.options" :key="option.id" :style="{
                    color: option.is_correct ? 'green' : 'black',
                    fontWeight: option.is_correct ? 'bold' : 'normal'
                }">
                    {{ option.text }}
                    <span v-if="option.is_correct">✔</span>
                </li>
            </ul>
        </div>
    </div>
</template>