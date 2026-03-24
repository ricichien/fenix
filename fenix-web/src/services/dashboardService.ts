import api from './api'

export const getStudentStats = async () => {
  const { data } = await api.get('/dashboard/student')
  return data
}

/**
 * Busca as estatísticas globais para o Dashboard do Professor
 * @param page Página atual (opcional)
 * @param limit Itens por página (opcional)
 */
export const getDashboardStats = async (page = 1, limit = 10) => {
  console.log('Chamando API do Dashboard...') // Se isso NÃO aparecer no console, a função não foi executada
  const { data } = await api.get('/dashboard', {
    params: { page, limit },
  })
  return data
}
