import { defineStore } from 'pinia'

export const useAuthStore = defineStore('auth', {
  state: () => ({
    role: localStorage.getItem('role') || '',
    userId: Number(localStorage.getItem('userId')) || null,
  }),
  actions: {
    setRole(role: string, userId: number) {
      this.role = role
      this.userId = userId
      localStorage.setItem('role', role)
      localStorage.setItem('userId', String(userId))
    },
    logout() {
      this.role = ''
      this.userId = null
      localStorage.removeItem('role')
      localStorage.removeItem('userId')
    },
  },
})
