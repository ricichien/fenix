<script setup lang="ts">
import { useRouter } from 'vue-router'
import { setCurrentUser } from '@/stores/user'

const router = useRouter()

const enterAsProfessor = () => {
    setCurrentUser({ id: 1, role: 'professor' })
    router.push('/professor')
}

const enterAsStudent = () => {
    setCurrentUser({ id: 2, role: 'student' })
    router.push('/student')
}
</script>

<template>
    <div>
        <h1>Escolha seu perfil</h1>
        <button @click="enterAsProfessor">Entrar como Professor</button>
        <button @click="enterAsStudent">Entrar como Aluno</button>
    </div>
</template>