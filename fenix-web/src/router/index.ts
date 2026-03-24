import { createRouter, createWebHistory } from 'vue-router'

import ProfessorLayout from '@/layouts/ProfessorLayout.vue'
import ProfessorDashboard from '../pages/professor/Dashboard.vue'
import ExamsList from '../pages/professor/ExamsList.vue'

import Exams from '../pages/student/Exams.vue'
import ExamTake from '../pages/student/ExamTake.vue'
import ChooseRole from '../pages/ChooseRole.vue'
import StudentLayout from '@/layouts/StudentLayout.vue'

const routes = [
  {
    path: '/',
    component: ChooseRole,
  },

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
      {
        path: 'exams/new',
        component: () => import('../pages/professor/ExamCreate.vue'),
      },
      {
        path: 'exams/:id',
        component: () => import('../pages/professor/ExamDetail.vue'),
      },
      {
        path: 'exams/:id/edit',
        component: () => import('../pages/professor/ExamEdit.vue'),
      },
    ],
  },

  {
    path: '/student',
    // component: StudentLayout,
    children: [
      {
        path: '',
        component: Exams,
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
