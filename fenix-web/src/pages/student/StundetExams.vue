<script setup>
import { ref, onMounted } from "vue";
import { getExams } from "@/services/examService";
import { useRouter } from "vue-router";

const exams = ref([]);
const router = useRouter();

const load = async () => {
    exams.value = await getExams();
};

const openExam = (id) => {
    router.push(`/student/exam/${id}`);
};

onMounted(load);
</script>

<template>
    <div>
        <h1>Provas disponíveis</h1>

        <ul>
            <li v-for="exam in exams" :key="exam.id">
                {{ exam.title }}
                <button @click="openExam(exam.id)">Iniciar</button>
            </li>
        </ul>
    </div>
</template>