<script setup>
import { ref, onMounted } from "vue";
import { useRoute } from "vue-router";
import { getExamById, submitExam } from "@/services/examService";

const route = useRoute();

const exam = ref(null);
const answers = ref({});
const result = ref(null);
const loading = ref(false);

const load = async () => {
    exam.value = await getExamById(route.params.id);
};

const submit = async () => {
    loading.value = true;

    try {
        result.value = await submitExam(route.params.id, {
            answers: answers.value,
        });
    } finally {
        loading.value = false;
    }
};

onMounted(load);
</script>

<template>
    <div v-if="exam">
        <h1>{{ exam.title }}</h1>

        <div v-for="q in exam.questions" :key="q.id">
            <p>{{ q.statement }}</p>

            <div v-for="opt in q.options" :key="opt.id">
                <label>
                    <input type="radio" :name="'q-' + q.id" :value="opt.id" v-model="answers[q.id]" />
                    {{ opt.text }}
                </label>
            </div>
        </div>

        <button @click="submit" :disabled="loading">
            {{ loading ? "Enviando..." : "Enviar" }}
        </button>

        <div v-if="result">
            <h2>Resultado</h2>
            <p>Score: {{ result.score }}</p>
            <p>Percentual: {{ result.percentage }}%</p>
        </div>
    </div>
</template>