import { createRouter, createWebHistory } from 'vue-router'

import ProfessorLayout from '@/layouts/ProfessorLayout.vue'
import ProfessorDashboard from '../pages/professor/Dashboard.vue'
import ExamsList from '../pages/professor/ExamsList.vue'

import StudentExams from '../pages/student/Exams.vue'
import ExamTake from '../pages/student/ExamTake.vue'

const routes = [
  {
    path: '/',
    redirect: '/student',
  },

  // PROFESSOR
  {
    path: '/professor',
    component: ProfessorLayout,
    children: [
      {
        path: '',
        component: ProfessorDashboard,
      },
      {
        path: 'exams',
        component: ExamsList,
      },
    ],
  },

  // ALUNO
  {
    path: '/student',
    children: [
      {
        path: '',
        component: StudentExams,
      },
      {
        path: 'exam/:id',
        component: ExamTake,
      },
    ],
  },
]

export default createRouter({
  history: createWebHistory(),
  routes,
})
