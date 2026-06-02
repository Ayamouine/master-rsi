import { create } from 'zustand'
import { authService } from '../services'

export const useAuthStore = create((set) => ({
  user: authService.getUser(),
  isAuthenticated: authService.isAuthenticated(),
  isLoading: false,
  error: null,

  // Actions
  login: async (email, password) => {
    set({ isLoading: true, error: null })
    try {
      const data = await authService.login(email, password)
      set({
        user: data.user,
        isAuthenticated: true,
        isLoading: false,
      })
      return data
    } catch (error) {
      set({
        error: error.response?.data?.message || 'Erreur de connexion',
        isLoading: false,
      })
      throw error
    }
  },

  register: async (name, email, password, passwordConfirmation, type) => {
    set({ isLoading: true, error: null })
    try {
      const data = await authService.register(name, email, password, passwordConfirmation, type)
      set({
        user: data.user,
        isAuthenticated: true,
        isLoading: false,
      })
      return data
    } catch (error) {
      set({
        error: error.response?.data?.message || 'Erreur lors de l\'inscription',
        isLoading: false,
      })
      throw error
    }
  },

  logout: async () => {
    try {
      await authService.logout()
      set({
        user: null,
        isAuthenticated: false,
        error: null,
      })
    } catch (error) {
      set({ error: 'Erreur lors de la déconnexion' })
    }
  },

  setUser: (user) => set({ user }),
  setError: (error) => set({ error }),
  clearError: () => set({ error: null }),
}))
