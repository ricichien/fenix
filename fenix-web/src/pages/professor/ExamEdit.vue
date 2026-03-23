<script setup lang="ts">
import { ref, onMounted } from "vue";
import { useRoute, useRouter } from "vue-router";
import { getExamWithAnswers, updateExam } from "@/services/examService";

const route = useRoute();
const router = useRouter();

const loading = ref(false);

const form = ref({
    title: "",
    description: "",
    questions: [] as any[],
});

const loadExam = async () => {
    const data = await getExamWithAnswers(route.params.id);

    form.value = data;
};

const addQuestion = () => {
    form.value.questions.push({
        statement: "",
        options: [
            { text: "", is_correct: false },
            { text: "", is_correct: false },
        ],
    });
};

const addOption = (question: any) => {
    question.options.push({ text: "", is_correct: false });
};

const markCorrect = (question: any, index: number) => {
    question.options.forEach((opt: any, i: number) => {
        opt.is_correct = i === index;
    });
};

const submit = async () => {
    loading.value = true;

    try {
        await updateExam(route.params.id, form.value);
        alert("Prova atualizada com sucesso!");
        router.push("/professor/exams");
    } finally {
        loading.value = false;
    }
};

onMounted(loadExam);
</script>

<template>
    <div>
        <h1>Editar prova</h1>

        <div>
            <label>Título</label>
            <input v-model="form.title" />
        </div>

        <div>
            <label>Descrição</label>
            <input v-model="form.description" />
        </div>

        <hr />

        <div v-for="(q, qIndex) in form.questions" :key="qIndex">
            <h3>Pergunta {{ qIndex + 1 }}</h3>

            <input v-model="q.statement" placeholder="Pergunta" />

            <div v-for="(opt, oIndex) in q.options" :key="oIndex">
                <input v-model="opt.text" placeholder="Alternativa" />

                <input type="radio" :name="'q-' + qIndex" :checked="opt.is_correct" @change="markCorrect(q, oIndex)" />
                Correta
            </div>

            <button @click="addOption(q)">+ Alternativa</button>
        </div>

        <button @click="addQuestion">+ Pergunta</button>

        <hr />

        <button @click="submit" :disabled="loading">
            {{ loading ? "Salvando..." : "Salvar" }}
        </button>
    </div>
</template>