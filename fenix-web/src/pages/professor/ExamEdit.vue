<script setup lang="ts">
import { ref, onMounted } from "vue";
import { useRoute, useRouter } from "vue-router";
import ExamForm, { type ExamPayload } from "@/components/professor/exam/ExamForm.vue";
import { getExamWithAnswers, updateExam } from "@/services/examService";

const route = useRoute();
const router = useRouter();

const isSaving = ref(false);
const isLoadingData = ref(true);
const examData = ref<ExamPayload | undefined>(undefined);

const loadData = async () => {
    try {
        const data = await getExamWithAnswers(route.params.id as string);
        examData.value = data;
    } catch (error) {
        alert("Erro ao carregar a prova.");
        router.push("/professor/exams");
    } finally {
        isLoadingData.value = false;
    }
};

const handleUpdate = async (payload: ExamPayload) => {
    isSaving.value = true;
    try {
        await updateExam(route.params.id as string, payload);
        alert("Prova atualizada!");
        router.push("/professor/exams");
    } catch (error) {
        alert("Erro ao salvar.");
    } finally {
        isSaving.value = false;
    }
};

onMounted(loadData);
</script>

<template>
    <div v-if="isLoadingData" class="loading">Carregando...</div>
    <ExamForm v-else-if="examData" :initialData="examData" :loading="isSaving" :isEditMode="true"
        @submit="handleUpdate" />
</template>