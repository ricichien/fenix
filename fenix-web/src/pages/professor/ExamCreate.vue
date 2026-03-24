<script setup lang="ts">
import { ref } from "vue";
import { useRouter } from "vue-router";
import ExamForm from "@/components/professor/exam/ExamForm.vue";
import type { ExamPayload } from "@/components/professor/exam/types";
import { createExam } from "@/services/examService";

const router = useRouter();
const isSaving = ref(false);

const handleSave = async (payload: ExamPayload) => {
    isSaving.value = true;

    try {
        await createExam(payload);
        alert("Prova criada com sucesso!");
        router.push("/professor/exams");
    } catch (error) {
        alert("Erro ao criar a prova.");
        console.error(error);
    } finally {
        isSaving.value = false;
    }
};
</script>

<template>
    <ExamForm :loading="isSaving" :is-edit-mode="false" @submit="handleSave" />
</template>

<style>
body {
    margin: 0;
    padding: 0;
    background-color: #f3f2f1;
}
</style>