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
    <div>
        <h3>Gabarito</h3>

        <div v-for="item in resultDetails" :key="item.question_id" :style="{
            border: '1px solid #ddd',
            borderRadius: '8px',
            padding: '12px',
            marginBottom: '12px',
            background: item.is_correct ? '#ecfdf5' : '#fef2f2'
        }">
            <p><strong>Pergunta:</strong> {{ item.question_statement }}</p>
            <p><strong>Sua resposta:</strong> {{ item.selected_text || 'Não respondida' }}</p>
            <p><strong>Correta:</strong> {{ item.correct_text }}</p>
            <p>
                <strong>Status:</strong>
                <span :style="{ color: item.is_correct ? 'green' : 'red' }">
                    {{ item.is_correct ? 'Acertou' : 'Errou' }}
                </span>
            </p>
        </div>
    </div>
</template>