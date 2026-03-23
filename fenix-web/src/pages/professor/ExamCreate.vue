<script setup lang="ts">
import ExamForm from "@/components/professor/ExamForm.vue";
import { createExam } from "@/services/examService";
import { useRouter } from "vue-router";

const router = useRouter();

const handleSave = async (payload: any) => {
    await createExam(payload);
    router.push("/professor/exams");
};
</script>

<template>
    <div>
        <h1>Nova prova</h1>
        <ExamForm @submit="handleSave" />
    </div>
</template>