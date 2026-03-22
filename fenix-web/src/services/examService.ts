import api from './api'

export const getExams = async () => {
  const { data } = await api.get('/exams')
  return data
}

export const getExamById = async (id: any) => {
  const { data } = await api.get(`/exams/${id}`)
  return data
}

export const submitExam = async (id: any, payload: any) => {
  const { data } = await api.post(`/exams/${id}/submit`, payload)
  return data
}
