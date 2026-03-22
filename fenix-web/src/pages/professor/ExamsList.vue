<template>
    <div>
        <h1>Lista de Provas</h1>

        <ul>
            <li v-for="exam in exams" :key="exam.id">
                {{ exam.title }}

                <button @click="startExam(exam.id)">
                    Start
                </button>
            </li>
        </ul>
    </div>
</template>