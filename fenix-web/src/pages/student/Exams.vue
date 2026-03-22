<template>
    <div>
        <h1>Exams</h1>

        <ul>
            <li v-for="exam in exams" :key="exam.id">
                {{ exam.title }}
                <button @click="openExam(exam.id)">Start</button>
            </li>
        </ul>
    </div>
</template>

<script setup>
import { ref, onMounted } from "vue";
import { getExams } from "@/services/examService";
import { useRouter } from "vue-router";

const router = useRouter();

const exams = ref([]);

const loadExams = async () => {
    exams.value = await getExams();
};

const openExam = (id) => {
    router.push(`/student/exams/${id}`);
};

onMounted(() => {
    loadExams();
});
</script>