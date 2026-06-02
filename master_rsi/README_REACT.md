# Master RSI - Plateforme de Gestion des Projets

Plateforme moderne de gestion des projets avec **React** en frontend et **Laravel** en backend API REST.

## 🎨 Design System

### Palette de couleurs
- **Bleu Marine**: `#1a3a6b` (couleur primaire)
- **Navy**: `#0d1b3e` (couleur primaire foncée)
- **Indigo**: `#3730a3` (accent)
- **Ambre**: `#d97706` (accent warmth)

### Typo graphis
- **Display**: Syne (titres et headings)
- **Monospace**: DM Mono (code et éléments techniques)
- **Base**: Système par défaut

## 📋 Architecture

```
master_rsi/
├── Backend (Laravel 12)
│   ├── app/Http/Controllers/Api/
│   ├── routes/api.php
│   └── ...
└── Frontend (React 18 + Vite)
    ├── src/
    │   ├── components/        (Composants réutilisables)
    │   ├── pages/            (Pages principales)
    │   ├── layouts/          (Layouts)
    │   ├── services/         (Appels API)
    │   ├── store/            (État global Zustand)
    │   ├── styles/           (Design system + globals)
    │   └── App.jsx
    └── index.html
```

## 🚀 Installation

### 1️⃣ Backend Laravel

```bash
cd master_rsi
cp .env.example .env

# Générer la clé APP
php artisan key:generate

# Installer les dépendances
composer install

# Migration de la base de données
php artisan migrate --force

# Lancer le serveur
php artisan serve
# Accessible à http://localhost:8000
```

### 2️⃣ Frontend React

```bash
# Installer les dépendances
npm install

# Copier les variables d'environnement
cp .env.frontend .env.local

# Lancer le serveur de développement
npm run dev
# Accessible à http://localhost:5173
```

## 📦 Dépendances Principales

### Backend
- Laravel 12
- Laravel Sanctum (authentification API)
- Laravel Tinker

### Frontend
- React 18
- React Router DOM v6
- Axios
- Zustand (gestion d'état)
- TanStack Query (React Query)
- Tailwind CSS / Custom CSS
- Vite

## 🔐 Authentification

### Flux de connexion
1. L'utilisateur se connecte via `/api/auth/login`
2. Le backend génère un token Sanctum
3. Le token est stocké localement (`localStorage`)
4. Les requêtes API incluent le token en header `Authorization: Bearer {token}`

### Protéger une route React
```jsx
<ProtectedRoute>
  <MonComposant />
</ProtectedRoute>
```

## 📡 API REST Endpoints

### Auth
- `POST /api/auth/login` - Connexion
- `POST /api/auth/register` - Inscription
- `POST /api/auth/logout` - Déconnexion (auth:sanctum)
- `GET /api/auth/user` - Utilisateur courant (auth:sanctum)

### Dashboard
- `GET /api/dashboard/stats` - Statistiques
- `GET /api/dashboard/about` - À propos

### Étudiants
- `GET /api/etudiants` - Liste paginée
- `GET /api/etudiants/{id}` - Détail
- `POST /api/etudiants` - Créer
- `PUT /api/etudiants/{id}` - Mettre à jour
- `DELETE /api/etudiants/{id}` - Supprimer

### Fichiers
- `GET /api/fichiers` - Liste
- `POST /api/fichiers` - Créer
- `GET /api/fichiers/{cne}` - Détail par CNE
- `PUT /api/fichiers/{cne}` - Mettre à jour
- `POST /api/fichiers/geocode` - Géocoder une adresse

### Images
- `GET /api/images` - Liste paginée
- `POST /api/images` - Upload
- `DELETE /api/images/{id}` - Supprimer

### Quiz
- `GET /api/quiz` - Questions
- `POST /api/quiz` - Soumettre les réponses

### Notes (Admin only)
- `GET /api/notes` - Liste
- `POST /api/notes` - Créer
- `PUT /api/notes/{id}` - Mettre à jour
- `DELETE /api/notes/{id}` - Supprimer

## 🎨 Composants Disponibles

### Sidebar
- Navigation collapsible avec animations
- Mise en surbrillance de la route active
- Responsive (mobile-friendly)

### Header
- Affichage du logo et titre
- Profil utilisateur
- Sticky en haut de page

### Layouts
- `AdminLayout` - Layout principal avec Sidebar et Header
- `LoginLayout` - Pour les pages d'authentification

### Pages
- `LoginPage` - Connexion avec design moderne
- `DashboardPage` - Tableau de bord avec statistiques
- (À ajouter: Étudiants, Projets, Images, Quiz, Notes)

## 🔧 Configuration

### CORS (Backend)
Le fichier `config/cors.php` est pré-configuré pour accepter les requêtes depuis:
- `http://localhost:3000`
- `http://localhost:5173`
- `http://127.0.0.1:3000`
- `http://127.0.0.1:5173`

### Vite (Frontend)
Configuration proxy pour `/api`:
```js
proxy: {
  '/api': {
    target: 'http://localhost:8000',
    changeOrigin: true,
  }
}
```

## 🎯 Développement

### Utiliser le design system
Les couleurs et espacements sont définis comme variables CSS:

```css
background-color: var(--primary-dark);
padding: var(--spacing-4);
border-radius: var(--radius-lg);
font-family: var(--font-display);
```

### Créer un nouveau service API
```javascript
export const monService = {
  getAll: async () => {
    const response = await api.get('/endpoint')
    return response.data
  },
}
```

### Utiliser l'état global (Zustand)
```javascript
import { useAuthStore } from '@/store/authStore'

export function MonComposant() {
  const user = useAuthStore((state) => state.user)
  return <p>{user.name}</p>
}
```

## 📱 Responsive Design

- **Desktop**: 1200px+
- **Tablet**: 768px - 1199px
- **Mobile**: < 768px

Breakpoints CSS media queries disponibles dans `design-system.css`.

## 🐛 Troubleshooting

### CORS Error
- Vérifier que Laravel est en cours d'exécution sur `http://localhost:8000`
- Vérifier la configuration dans `config/cors.php`
- Vérifier les origines dans `SANCTUM_STATEFUL_DOMAINS`

### Token non envoyé
- Vérifier le localStorage: `localStorage.getItem('auth_token')`
- Vérifier le header Authorization dans Network tab
- Vérifier l'intercepteur dans `services/api.js`

### Migrations échouées
```bash
php artisan migrate:refresh
php artisan migrate:fresh
```

## 📚 Ressources

- [Laravel Documentation](https://laravel.com/docs)
- [React Documentation](https://react.dev)
- [Zustand Documentation](https://zustand-demo.vercel.app)
- [Vite Documentation](https://vitejs.dev)

## 📝 License

MIT

---

**Note**: Ceci est un projet académique pour Master RSI.
