import api from './api'

export const authService = {
  login: async (email, password) => {
    const response = await api.post('/auth/login', { email, password })
    if (response.data.token) {
      localStorage.setItem('auth_token', response.data.token)
      localStorage.setItem('user', JSON.stringify(response.data.user))
    }
    return response.data
  },

  register: async (name, email, password, passwordConfirmation, type) => {
    const response = await api.post('/auth/register', {
      name,
      email,
      password,
      password_confirmation: passwordConfirmation,
      type,
    })
    if (response.data.token) {
      localStorage.setItem('auth_token', response.data.token)
      localStorage.setItem('user', JSON.stringify(response.data.user))
    }
    return response.data
  },

  logout: async () => {
    await api.post('/auth/logout')
    localStorage.removeItem('auth_token')
    localStorage.removeItem('user')
  },

  getCurrentUser: async () => {
    const response = await api.get('/auth/user')
    return response.data
  },

  getUser: () => {
    const user = localStorage.getItem('user')
    return user ? JSON.parse(user) : null
  },

  getToken: () => {
    return localStorage.getItem('auth_token')
  },

  isAuthenticated: () => {
    return !!localStorage.getItem('auth_token')
  },
}

export const dashboardService = {
  getStats: async () => {
    const response = await api.get('/dashboard/stats')
    return response.data
  },

  getAbout: async () => {
    const response = await api.get('/dashboard/about')
    return response.data
  },
}

export const etudiants = {
  getAll: async (page = 1) => {
    const response = await api.get(`/etudiants?page=${page}`)
    return response.data
  },

  getById: async (id) => {
    const response = await api.get(`/etudiants/${id}`)
    return response.data
  },

  create: async (data) => {
    const response = await api.post('/etudiants', data)
    return response.data
  },

  update: async (id, data) => {
    const response = await api.put(`/etudiants/${id}`, data)
    return response.data
  },

  delete: async (id) => {
    const response = await api.delete(`/etudiants/${id}`)
    return response.data
  },
}

export const filesService = {
  getAll: async (page = 1) => {
    const response = await api.get(`/fichiers?page=${page}`)
    return response.data
  },

  getByCNE: async (cne) => {
    const response = await api.get(`/fichiers/${cne}`)
    return response.data
  },

  create: async (data) => {
    const response = await api.post('/fichiers', data)
    return response.data
  },

  update: async (cne, data) => {
    const response = await api.put(`/fichiers/${cne}`, data)
    return response.data
  },

  geocode: async (address) => {
    const response = await api.post('/fichiers/geocode', { address })
    return response.data
  },
}

export const imagesService = {
  getAll: async (page = 1) => {
    const response = await api.get(`/images?page=${page}`)
    return response.data
  },

  upload: async (file, description, type) => {
    const formData = new FormData()
    formData.append('image', file)
    formData.append('description', description)
    formData.append('type', type)

    const response = await api.post('/images', formData, {
      headers: { 'Content-Type': 'multipart/form-data' },
    })
    return response.data
  },

  delete: async (id) => {
    const response = await api.delete(`/images/${id}`)
    return response.data
  },
}

export const quizService = {
  getAll: async () => {
    const response = await api.get('/quiz')
    return response.data
  },

  submit: async (etudiantId, answers) => {
    const response = await api.post('/quiz', {
      etudiant_id: etudiantId,
      answers,
    })
    return response.data
  },
}

export const notesService = {
  getAll: async (page = 1) => {
    const response = await api.get(`/notes?page=${page}`)
    return response.data
  },

  create: async (data) => {
    const response = await api.post('/notes', data)
    return response.data
  },

  update: async (id, data) => {
    const response = await api.put(`/notes/${id}`, data)
    return response.data
  },

  delete: async (id) => {
    const response = await api.delete(`/notes/${id}`)
    return response.data
  },
}
