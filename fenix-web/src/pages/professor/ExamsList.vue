<script setup lang="ts">
import { ref, onMounted } from "vue";
import { useRouter } from "vue-router";
import { getExams, deleteExam } from "@/services/examService";

const router = useRouter();
const exams = ref([]);
const loading = ref(false);

const loadExams = async () => {
    loading.value = true;
    try {
        exams.value = await getExams();
    } finally {
        loading.value = false;
    }
};

const goToCreate = () => {
    router.push("/professor/exams/new");
};

const goToDetail = (id: number) => {
    router.push(`/professor/exams/${id}`);
};

const goToEdit = (id: number) => {
    router.push(`/professor/exams/${id}/edit`);
};

const removeExam = async (id: number) => {
    if (!confirm("Tem certeza que deseja excluir esta prova?")) return;
    await deleteExam(id);
    await loadExams();
};

onMounted(loadExams);
</script>

<template>
    <div>
        <div class="header">
            <h1>Provas</h1>
            <button @click="goToCreate">Nova prova</button>
        </div>

        <p v-if="loading">Carregando...</p>

        <table v-else>
            <thead>
                <tr>
                    <th>Título</th>
                    <th>Descrição</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="exam in exams" :key="exam.id">
                    <td>{{ exam.title }}</td>
                    <td>{{ exam.description }}</td>
                    <td>
                        <button @click="goToDetail(exam.id)">Ver</button>
                        <button @click="goToEdit(exam.id)">Editar</button>
                        <button @click="removeExam(exam.id)">Excluir</button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>