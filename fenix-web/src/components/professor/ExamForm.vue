<script setup lang="ts">
import { ref } from "vue";

const emit = defineEmits(["submit"]);

const title = ref("");
const description = ref("");
const questions = ref([
    {
        statement: "",
        options: [
            { text: "", is_correct: false },
            { text: "", is_correct: false },
            { text: "", is_correct: false },
            { text: "", is_correct: false },
        ],
    },
]);

const addQuestion = () => {
    questions.value.push({
        statement: "",
        options: [
            { text: "", is_correct: false },
            { text: "", is_correct: false },
            { text: "", is_correct: false },
            { text: "", is_correct: false },
        ],
    });
};

const addOption = (questionIndex: number) => {
    questions.value[questionIndex].options.push({
        text: "",
        is_correct: false,
    });
};

const setCorrectOption = (questionIndex: number, optionIndex: number) => {
    questions.value[questionIndex].options.forEach((option, index) => {
        option.is_correct = index === optionIndex;
    });
};

const submitForm = () => {
    const payload = {
        title: title.value,
        description: description.value,
        questions: questions.value,
    };

    emit("submit", payload);
};
</script>

<template>
    <form @submit.prevent="submitForm">
        <div>
            <label>Título</label>
            <input v-model="title" type="text" />
        </div>

        <div>
            <label>Descrição</label>
            <textarea v-model="description"></textarea>
        </div>

        <div v-for="(question, qIndex) in questions" :key="qIndex">
            <h3>Questão {{ qIndex + 1 }}</h3>

            <input v-model="question.statement" type="text" placeholder="Enunciado" />

            <div v-for="(option, oIndex) in question.options" :key="oIndex">
                <input v-model="option.text" type="text" placeholder="Alternativa" />
                <label>
                    <input type="radio" :name="'correct-' + qIndex" :checked="option.is_correct"
                        @change="setCorrectOption(qIndex, oIndex)" />
                    Correta
                </label>
            </div>

            <button type="button" @click="addOption(qIndex)">Adicionar alternativa</button>
        </div>

        <button type="button" @click="addQuestion">Adicionar questão</button>
        <button type="submit">Salvar prova</button>
    </form>
</template>