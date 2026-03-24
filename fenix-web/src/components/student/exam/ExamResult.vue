<script setup lang="ts">
type ResultDetail = {
    question_id: number
    question_statement: string
    selected_option_id: number | null
    selected_text: string | null
    correct_option_id: number | null
    correct_text: string | null
    is_correct: boolean
}

defineProps<{
    resultDetails: ResultDetail[]
}>()
</script>

<template>
    <div class="review-section">
        <h3 class="review-title">Revisão das Respostas</h3>

        <div v-for="(item, index) in resultDetails" :key="item.question_id" class="review-card"
            :class="item.is_correct ? 'card-success' : 'card-error'">

            <div class="card-header">
                <span class="q-index">{{ index + 1 }}.</span>
                <p class="q-statement">{{ item.question_statement }}</p>

                <div class="status-icon">
                    <svg v-if="item.is_correct" viewBox="0 0 24 24" class="icon success-icon">
                        <path d="M20 6L9 17l-5-5" stroke="currentColor" stroke-width="3" stroke-linecap="round"
                            stroke-linejoin="round" fill="none" />
                    </svg>
                    <svg v-else viewBox="0 0 24 24" class="icon error-icon">
                        <path d="M18 6L6 18M6 6l12 12" stroke="currentColor" stroke-width="3" stroke-linecap="round"
                            stroke-linejoin="round" fill="none" />
                    </svg>
                </div>
            </div>

            <div class="answers-comparison">
                <div class="answer-row">
                    <span class="label">Sua resposta:</span>
                    <span class="text" :class="{ 'text-error': !item.is_correct }">
                        {{ item.selected_text || 'Não respondida' }}
                    </span>
                </div>

                <div v-if="!item.is_correct" class="answer-row">
                    <span class="label">Resposta correta:</span>
                    <span class="text text-success">{{ item.correct_text }}</span>
                </div>
            </div>

            <div class="status-badge" :class="item.is_correct ? 'badge-success' : 'badge-error'">
                {{ item.is_correct ? 'Correto' : 'Incorreto' }}
            </div>
        </div>
    </div>
</template>

<style scoped>
.review-section {
    margin-top: 24px;
    width: 100%;
}

.review-title {
    font-size: 18px;
    color: #323130;
    margin-bottom: 16px;
    font-weight: 600;
}

.review-card {
    background: white;
    border-radius: 4px;
    padding: 24px;
    margin-bottom: 16px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
    position: relative;
    border-left: 6px solid #ddd;
    transition: transform 0.2s;
}

/* Cores Laterais Estilo Forms */
.card-success {
    border-left-color: #107c10;
}

.card-error {
    border-left-color: #a4262c;
}

.card-header {
    display: flex;
    gap: 12px;
    margin-bottom: 20px;
    padding-right: 40px;
    /* Espaço para o ícone flutuante */
}

.q-index {
    font-weight: 700;
    color: #323130;
}

.q-statement {
    font-size: 16px;
    font-weight: 600;
    color: #323130;
    margin: 0;
}

.status-icon {
    position: absolute;
    right: 24px;
    top: 24px;
}

.icon {
    width: 24px;
    height: 24px;
}

.success-icon {
    color: #107c10;
}

.error-icon {
    color: #a4262c;
}

/* Comparativo de Respostas */
.answers-comparison {
    background: #f8fafc;
    padding: 16px;
    border-radius: 4px;
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.answer-row {
    display: flex;
    font-size: 14px;
    gap: 8px;
}

.label {
    color: #605e5c;
    font-weight: 500;
    min-width: 120px;
}

.text {
    color: #323130;
    font-weight: 600;
}

.text-success {
    color: #107c10;
}

.text-error {
    color: #a4262c;
}

/* Badge de Status */
.status-badge {
    display: inline-block;
    margin-top: 16px;
    padding: 4px 12px;
    border-radius: 12px;
    font-size: 12px;
    font-weight: 700;
    text-transform: uppercase;
}

.badge-success {
    background: #dff6dd;
    color: #107c10;
}

.badge-error {
    background: #fde7e9;
    color: #a4262c;
}
</style>