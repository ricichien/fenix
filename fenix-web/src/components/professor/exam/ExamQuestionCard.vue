<script setup lang="ts">
import type { ExamQuestion } from "./types";

const props = withDefaults(
    defineProps<{
        question: ExamQuestion;
        index: number;
        readonly?: boolean;
        canMoveUp?: boolean;
        canMoveDown?: boolean;
        canRemoveQuestion?: boolean;
    }>(),
    {
        readonly: false,
        canMoveUp: false,
        canMoveDown: false,
        canRemoveQuestion: true,
    }
);

const emit = defineEmits<{
    (e: "move-up"): void;
    (e: "move-down"): void;
    (e: "remove-question"): void;
}>();

const createBlankOption = () => ({
    text: "",
    is_correct: false,
});

const addOption = () => {
    props.question.options.push(createBlankOption());
};

const removeOption = (optionIndex: number) => {
    if (props.question.options.length <= 2) {
        alert("Cada questão deve ter no mínimo duas alternativas.");
        return;
    }

    const confirmed = window.confirm("Excluir esta alternativa?");
    if (!confirmed) return;

    props.question.options.splice(optionIndex, 1);
};

const markCorrect = (optionIndex: number) => {
    props.question.options.forEach((option, index) => {
        option.is_correct = index === optionIndex;
    });
};
</script>

<template>
    <section class="question-card" :class="{ 'question-card--readonly': readonly }">
        <div class="question-card__header">
            <div class="question-meta">
                <span class="question-badge">Questão {{ index + 1 }}</span>

                <div v-if="!readonly" class="move-actions">
                    <button type="button" class="small-btn" :disabled="!canMoveUp" @click="emit('move-up')">
                        Subir
                    </button>

                    <button type="button" class="small-btn" :disabled="!canMoveDown" @click="emit('move-down')">
                        Descer
                    </button>
                </div>
            </div>

            <button v-if="!readonly && canRemoveQuestion" type="button" class="text-danger-btn"
                @click="emit('remove-question')">
                Excluir
            </button>
        </div>

        <div class="question-card__body">
            <template v-if="readonly">
                <h2 class="statement-text">
                    {{ question.statement || "Sem enunciado" }}
                </h2>

                <div class="options-view-list">
                    <div v-for="(option, optionIndex) in question.options" :key="option.id ?? optionIndex"
                        class="opt-view-item" :class="{ 'is-correct': option.is_correct }">
                        <span class="opt-indicator">
                            {{ option.is_correct ? "✓" : "" }}
                        </span>

                        <span class="opt-text">
                            {{ option.text || "Alternativa sem texto" }}
                        </span>

                        <span v-if="option.is_correct" class="correct-label">
                            Resposta correta
                        </span>
                    </div>
                </div>
            </template>

            <template v-else>
                <textarea v-model="question.statement" class="input-statement"
                    placeholder="Digite o enunciado da pergunta..." rows="3"></textarea>

                <div class="options-edit-list">
                    <div v-for="(option, optionIndex) in question.options" :key="option.id ?? optionIndex"
                        class="opt-edit-item">
                        <label class="correct-toggle"
                            :title="option.is_correct ? 'Resposta correta' : 'Marcar como correta'">
                            <input type="radio" :name="'correct-' + index" :checked="option.is_correct"
                                @change="markCorrect(optionIndex)" />
                            <span class="radio-ui"></span>
                        </label>

                        <input v-model="option.text" class="input-option"
                            :placeholder="'Alternativa ' + (optionIndex + 1)" />
                    </div>
                </div>
            </template>
        </div>
    </section>
</template>

<style scoped>
.question-card {
    background: white;
    border: 1px solid #ececec;
    margin-bottom: 16px;
    overflow: hidden;
}

.question-card__header {
    display: flex;
    justify-content: space-between;
    gap: 16px;
    padding: 16px 20px;
    border-bottom: 1px solid #f0f0f0;
}

.question-meta {
    display: flex;
    align-items: center;
    gap: 12px;
    flex-wrap: wrap;
}

.question-badge {
    background: #fff7ed;
    color: #c2410c;
    border: 1px solid #fed7aa;
    padding: 4px 10px;
    border-radius: 999px;
    font-size: 12px;
    font-weight: 700;
}

.move-actions {
    display: flex;
    gap: 8px;
}

.small-btn,
.text-danger-btn,
.remove-option-btn,
.add-option-btn {
    border-radius: 8px;
    cursor: pointer;
    transition: 0.2s;
}

.small-btn {
    border: 1px solid #d1d5db;
    background: #fff;
    padding: 6px 10px;
    font-size: 13px;
}

.small-btn:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

.text-danger-btn,
.btn-remove-opt {
    border: 1px solid #fecaca;
    background: #fff;
    color: #b91c1c;
    padding: 8px 12px;
}

.question-card__body {
    padding: 20px;
}

.input-statement {
    width: 100%;
    border: 1px solid #d9d9d9;
    border-radius: 8px;
    padding: 12px;
    resize: vertical;
    outline: none;
    margin-bottom: 16px;
    font-size: 15px;
    box-sizing: border-box;
}

.input-option {
    flex: 1;
    border: 1px solid #d9d9d9;
    border-radius: 8px;
    padding: 10px 12px;
    outline: none;
    min-width: 0;
}

.input-statement:focus,
.input-option:focus {
    border-color: #ea580c;
}

.options-edit-list,
.options-view-list {
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.opt-edit-item {
    display: grid;
    grid-template-columns: auto 1fr auto;
    gap: 10px;
    align-items: center;
}

.correct-toggle {
    display: flex;
    align-items: center;
    cursor: pointer;
}

.correct-toggle input {
    display: none;
}

.radio-ui {
    width: 20px;
    height: 20px;
    border: 2px solid #605e5c;
    border-radius: 50%;
    display: block;
    position: relative;
}

.correct-toggle input:checked+.radio-ui {
    border-color: #107c10;
    background: #107c10;
}

.correct-toggle input:checked+.radio-ui::after {
    content: "✓";
    color: white;
    font-size: 12px;
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
}

.btn-remove-opt {
    background: #fff;
    font-size: 13px;
}

.btn-add-opt {
    margin-top: 12px;
    border: 1px dashed #ea580c;
    background: #fff;
    color: #ea580c;
    padding: 10px 12px;
    width: 100%;
    font-weight: 600;
}

.statement-text {
    margin: 0 0 16px;
    font-size: 18px;
    line-height: 1.5;
    color: #222;
}

.opt-view-item {
    border: 1px solid #e5e7eb;
    border-radius: 10px;
    padding: 12px 14px;
    display: flex;
    align-items: center;
    gap: 12px;
}

.opt-view-item.is-correct {
    border-color: #bbf7d0;
    background: #f0fdf4;
}

.opt-indicator {
    width: 22px;
    height: 22px;
    border: 2px solid #c8c6c4;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    font-size: 12px;
}

.opt-view-item.is-correct .opt-indicator {
    background: #107c10;
    border-color: #107c10;
    color: white;
}

.opt-text {
    color: #333;
}

.opt-view-item.is-correct .opt-text {
    font-weight: 600;
    color: #107c10;
}

.correct-label {
    margin-left: auto;
    font-size: 11px;
    font-weight: 800;
    text-transform: uppercase;
    color: #107c10;
    letter-spacing: 0.5px;
}
</style>