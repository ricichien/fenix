// src/stores/user.ts (ou simples localStorage)
export const getCurrentUser = () => {
  return JSON.parse(localStorage.getItem('user') || 'null')
}

export const setCurrentUser = (user: any) => {
  localStorage.setItem('user', JSON.stringify(user))
}
