<script setup lang="ts">
import { ref, watch } from "vue";
import ExamQuestionCard from "./ExamQuestionCard.vue";
import type { ExamPayload, ExamQuestion, ExamOption } from "./exam/types";

export type { ExamPayload } from "./exam/types";

const props = defineProps<{
    initialData?: ExamPayload;
    loading?: boolean;
    isEditMode?: boolean;
}>();

const emit = defineEmits<{
    (e: "submit", payload: ExamPayload): void;
}>();

const createBlankOption = (): ExamOption => ({
    text: "",
    is_correct: false,
});

const createBlankQuestion = (): ExamQuestion => ({
    statement: "",
    options: [
        createBlankOption(),
        createBlankOption(),
        createBlankOption(),
        createBlankOption(),
    ],
});

const normalizeQuestions = (questions?: ExamQuestion[]): ExamQuestion[] => {
    if (!questions || questions.length === 0) {
        return [createBlankQuestion()];
    }

    return questions.map((question) => ({
        id: question.id,
        statement: question.statement ?? "",
        options:
            question.options?.length > 0
                ? question.options.map((option) => ({
                    id: option.id,
                    text: option.text ?? "",
                    is_correct: !!option.is_correct,
                }))
                : [
                    createBlankOption(),
                    createBlankOption(),
                    createBlankOption(),
                    createBlankOption(),
                ],
    }));
};

const normalizePayload = (data: ExamPayload): ExamPayload => ({
    id: data.id,
    title: data.title ?? "",
    description: data.description ?? "",
    questions: normalizeQuestions(data.questions),
});

const form = ref<ExamPayload>({
    title: "",
    description: "",
    questions: [createBlankQuestion()],
});

watch(
    () => props.initialData,
    (newData) => {
        if (!newData) return;
        form.value = normalizePayload(newData);
    },
    { immediate: true, deep: true }
);

const addQuestion = () => {
    form.value.questions.push(createBlankQuestion());
};

const removeQuestion = (index: number) => {
    if (form.value.questions.length === 1) {
        alert("A prova deve ter no mínimo uma questão.");
        return;
    }

    const confirmed = window.confirm("Excluir esta pergunta?");
    if (!confirmed) return;

    form.value.questions.splice(index, 1);
};

const moveQuestion = (index: number, direction: "up" | "down") => {
    const newIndex = direction === "up" ? index - 1 : index + 1;

    if (newIndex < 0 || newIndex >= form.value.questions.length) return;

    const [question] = form.value.questions.splice(index, 1);
    form.value.questions.splice(newIndex, 0, question);
};

const validateAndSubmit = () => {
    if (!form.value.title.trim()) {
        alert("O título é obrigatório.");
        return;
    }

    for (let i = 0; i < form.value.questions.length; i++) {
        const question = form.value.questions[i];

        if (!question.statement.trim()) {
            alert(`Preencha o enunciado da Questão ${i + 1}.`);
            return;
        }

        if (question.options.length < 2) {
            alert(`A Questão ${i + 1} precisa de pelo menos duas alternativas.`);
            return;
        }

        const hasCorrect = question.options.some((option) => option.is_correct);
        if (!hasCorrect) {
            alert(`Marque a resposta correta da Questão ${i + 1}.`);
            return;
        }

        const hasEmptyOption = question.options.some((option) => !option.text.trim());
        if (hasEmptyOption) {
            alert(`Preencha todas as alternativas da Questão ${i + 1}.`);
            return;
        }
    }

    emit("submit", {
        ...form.value,
        title: form.value.title.trim(),
        description: form.value.description.trim(),
    });
};
</script>

<template>
    <div class="editor-layout">
        <header class="editor-header">
            <div class="header-content">
                <div class="title-edit-zone">
                    <input v-model="form.title" class="input-title-main" placeholder="Título da Avaliação" />
                    <input v-model="form.description" class="input-desc-main" placeholder="Descrição da prova..." />
                </div>
            </div>
        </header>

        <main class="editor-body">
            <div class="content-container">
                <ExamQuestionCard v-for="(question, qIndex) in form.questions" :key="question.id ?? qIndex"
                    :question="question" :index="qIndex" :can-move-up="qIndex > 0"
                    :can-move-down="qIndex < form.questions.length - 1" :can-remove-question="form.questions.length > 1"
                    @move-up="moveQuestion(qIndex, 'up')" @move-down="moveQuestion(qIndex, 'down')"
                    @remove-question="removeQuestion(qIndex)" />

                <div class="editor-actions">
                    <button type="button" class="btn-add-q" @click="addQuestion">
                        + Adicionar Pergunta
                    </button>

                    <div class="save-zone">
                        <button type="button" class="btn-save-main" :disabled="loading" @click="validateAndSubmit">
                            {{ loading ? "Salvando..." : isEditMode ? "Salvar" : "Criar Prova" }}
                        </button>
                    </div>
                </div>
            </div>
        </main>
    </div>
</template>

<style scoped>
.editor-layout {
    min-height: 100vh;
    background-color: #f3f2f1;
    font-family: "Segoe UI", system-ui, sans-serif;
}

.editor-header {
    background-color: #ea580c;
    color: white;
    padding: 30px 20px 70px;
    display: flex;
    justify-content: center;
}

.header-content {
    max-width: 800px;
    width: 100%;
}

.title-edit-zone {
    text-align: center;
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.input-title-main {
    background: transparent;
    border: none;
    border-bottom: 2px solid rgba(255, 255, 255, 0.2);
    color: white;
    font-size: 28px;
    font-weight: 700;
    text-align: center;
    padding: 8px;
    outline: none;
    transition: 0.2s;
}

.input-title-main:focus {
    border-bottom-color: white;
}

.input-title-main::placeholder {
    color: rgba(255, 255, 255, 0.7);
}

.input-desc-main {
    background: transparent;
    border: none;
    color: rgba(255, 255, 255, 0.9);
    font-size: 16px;
    text-align: center;
    outline: none;
}

.input-desc-main::placeholder {
    color: rgba(255, 255, 255, 0.6);
}

.editor-body {
    margin-top: -40px;
    padding: 0 20px 100px;
    display: flex;
    justify-content: center;
}

.content-container {
    max-width: 800px;
    width: 100%;
}

.editor-actions {
    margin-top: 16px;
}

.btn-add-q {
    width: 100%;
    background: white;
    border: 2px dashed #d2d0ce;
    color: #605e5c;
    padding: 16px;
    font-size: 16px;
    font-weight: 600;
    cursor: pointer;
    margin-bottom: 30px;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 12px;
    border-radius: 12px;
}

.btn-add-q:hover {
    border-color: #ea580c;
    color: #ea580c;
}

.save-zone {
    text-align: right;
}

.btn-save-main {
    background: #ea580c;
    color: white;
    border: none;
    padding: 14px 40px;
    font-size: 18px;
    font-weight: 700;
    cursor: pointer;
}

.btn-save-main:hover {
    background: #c2410c;
}

.btn-save-main:disabled {
    background: #d2d0ce;
    cursor: not-allowed;
    box-shadow: none;
}

@media (max-width: 720px) {
    .save-zone {
        text-align: stretch;
    }

    .btn-save-main {
        width: 100%;
    }
}
</style>