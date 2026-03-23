import api from './api'
import { getCurrentUser } from '@/stores/user'

export const getExams = async () => {
  const user = getCurrentUser()

  const { data } = await api.get('/exams', {
    params: {
      student_id: user?.id,
    },
  })

  return data
}

export const getExamById = async (id: any) => {
  const user = getCurrentUser()

  const { data } = await api.get(`/exams/${id}`, {
    params: {
      student_id: user?.id,
    },
  })

  return data
}

export const getExamWithAnswers = async (id: any) => {
  const { data } = await api.get(`/exams/${id}/edit`)
  return data
}

export const submitExam = async (id: any, answers: any) => {
  const user = getCurrentUser()

  const { data } = await api.post(`/exams/${id}/submit`, {
    student_id: user?.id,
    answers,
  })

  return data
}

export const createExam = async (payload: any) => {
  const { data } = await api.post('/exams', payload)
  return data
}

export const updateExam = async (id: number | string, payload: any) => {
  const { data } = await api.put(`/exams/${id}`, payload)
  return data
}

export const deleteExam = async (id: number | string) => {
  const { data } = await api.delete(`/exams/${id}`)
  return data
}
