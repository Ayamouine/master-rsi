# 📂 Structure Complète du Projet

## 🌳 Arborescence

```
master_rsi/
│
├── 📄 artisan                          ← CLI Laravel
├── 📄 composer.json                    ← Dépendances PHP
├── 📄 composer.lock
├── 📄 package.json                     ← Dépendances Node (MODIFIÉ)
├── 📄 package-lock.json
├── 📄 index.html                       ← Template HTML (MODIFIÉ)
│
├── 📁 app/
│   └── Http/
│       └── Controllers/
│           └── Api/                    ← ✨ NOUVEAU
│               ├── AuthController.php
│               ├── DashboardController.php
│               ├── EtudiantController.php
│               ├── ImageController.php
│               ├── QuizController.php
│               └── NotesController.php
│
├── 📁 bootstrap/
│
├── 📁 config/
│   ├── cors.php                        ← ✨ NOUVEAU - CORS config
│   └── ...
│
├── 📁 database/
│
├── 📁 public/
│   └── dist/                           ← Build React output
│       └── (fichiers compilés)
│
├── 📁 resources/                       ← Ancien dossier Blade
│   └── (gardé pour compatibilité)
│
├── 📁 routes/
│   ├── api.php                         ← ✨ NOUVEAU - Routes API
│   ├── web.php
│   └── ...
│
├── 📁 storage/
│
├── 📁 src/                             ← ✨ NOUVEAU - Frontend React
│   ├── App.jsx                         ← Composant principal
│   ├── main.jsx                        ← Point d'entrée Vite
│   │
│   ├── 📁 components/                  ← Composants réutilisables
│   │   ├── index.js                    ← Export centralisé
│   │   ├── Sidebar.jsx                 ← Navigation collapsible
│   │   ├── Sidebar.css
│   │   ├── Header.jsx                  ← En-tête avec profil
│   │   ├── Header.css
│   │   ├── Button.jsx                  ← Boutons réutilisables
│   │   ├── Button.css
│   │   ├── Card.jsx                    ← Cartes
│   │   └── Card.css
│   │
│   ├── 📁 pages/                       ← Pages principales
│   │   ├── LoginPage.jsx               ← Connexion
│   │   ├── LoginPage.css
│   │   ├── DashboardPage.jsx           ← Tableau de bord
│   │   └── DashboardPage.css
│   │
│   ├── 📁 layouts/                     ← Layouts
│   │   ├── AdminLayout.jsx             ← Layout main (Sidebar + Header)
│   │   └── AdminLayout.css
│   │
│   ├── 📁 services/                    ← Appels API
│   │   ├── api.js                      ← Axios + intercepteurs
│   │   └── index.js                    ← Services métier
│   │
│   ├── 📁 store/                       ← État global (Zustand)
│   │   └── authStore.js                ← Auth state management
│   │
│   ├── 📁 hooks/                       ← Hooks React
│   │   └── (à créer selon besoins)
│   │
│   └── 📁 styles/                      ← Styles globaux
│       ├── design-system.css           ← Variables CSS + reset
│       └── globals.css                 ← Utilitaires CSS
│
├── 📁 tests/
│
├── 📁 vendor/                          ← Dépendances Composer
│
├── 📁 node_modules/                    ← Dépendances NPM
│   └── (généré automatiquement)
│
├── 📄 .env                             ← Configuration Laravel
├── 📄 .env.example
├── 📄 .env.frontend                    ← ✨ NOUVEAU - Config React
├── 📄 .gitignore                       ← Git ignore Laravel
├── 📄 .gitignore.frontend              ← ✨ NOUVEAU - Git ignore React
│
├── 📄 vite.config.js                   ← ✨ MODIFIÉ - Config Vite
├── 📄 tailwind.config.js               ← ✨ NOUVEAU - Config Tailwind
├── 📄 postcss.config.js                ← ✨ NOUVEAU - Config PostCSS
├── 📄 phpunit.xml
│
├── 📄 README.md                        ← README original
├── 📄 README_REACT.md                  ← ✨ NOUVEAU - Guide React
├── 📄 SETUP.md                         ← ✨ NOUVEAU - Guide démarrage
└── 📄 CHANGELOG.md                     ← ✨ NOUVEAU - Changelog
```

## 📍 Fichiers Clés

### Backend API

| Fichier | Description |
|---------|-------------|
| `routes/api.php` | Toutes les routes API RESTful |
| `app/Http/Controllers/Api/AuthController.php` | Auth avec Sanctum |
| `config/cors.php` | Configuration CORS |
| `.env` | Variables backend |

### Frontend React

| Fichier | Description |
|---------|-------------|
| `src/App.jsx` | Composant principal + routes |
| `src/main.jsx` | Point d'entrée Vite |
| `index.html` | Template HTML |
| `src/services/api.js` | Axios client + intercepteurs |
| `src/store/authStore.js` | Gestion auth Zustand |
| `src/styles/design-system.css` | Variables CSS + design system |

### Configuration

| Fichier | Rôle |
|---------|------|
| `vite.config.js` | Config build + dev server React |
| `tailwind.config.js` | Thème Tailwind personnalisé |
| `package.json` | Scripts et dépendances NPM |
| `.env.frontend` | Variables React |

### Documentation

| Fichier | Contenu |
|---------|---------|
| `README_REACT.md` | Guide complet du projet |
| `SETUP.md` | Instructions démarrage |
| `CHANGELOG.md` | Liste changements |
| `STRUCTURE.md` | Ce fichier |

## 🚀 Commandes Principales

### Backend
```bash
php artisan serve           # Démarrer Laravel (port 8000)
php artisan migrate         # Appliquer migrations
php artisan tinker          # Shell PHP interactif
```

### Frontend
```bash
npm run dev                 # Démarrer Vite (port 5173)
npm run build               # Build production
npm run preview             # Prévisualiser build
```

## 🔗 Connexions

```
Frontend (React)
    ↓ HTTP/HTTPS
    ↓ Axios Requests
    ↓ URLs: /api/*
    ↓
Backend (Laravel API)
    ↓ Routes → Controllers
    ↓ Models → Database
    ↓ JSON Response
    ↓
Frontend (React)
    ↓ Zustand Store
    ↓ Components Update
```

## 📦 Organisation des Composants

```
components/
├── Layout Components
│   ├── Sidebar.jsx        ← Navigation
│   └── Header.jsx         ← En-tête
│
├── UI Components
│   ├── Button.jsx         ← Boutons
│   ├── Card.jsx           ← Cartes
│   └── ... (à ajouter)
│
└── Feature Components
    ├── (à créer)
    └── (à créer)
```

## 🎨 Design System Files

```
styles/
├── design-system.css      ← CSS Variables + Reset
│   ├── Couleurs
│   ├── Fonts
│   ├── Espacements
│   ├── Shadows
│   └── Animations
│
└── globals.css            ← Utilitaires
    ├── Flexbox helpers
    ├── Margin helpers
    ├── Text utilities
    └── Display utilities
```

## 🔐 Authentification Flow

```
1. User submits login form
   ↓
2. POST /api/auth/login (email, password)
   ↓
3. Backend: Hash check + Token generation
   ↓
4. Response: { token, user }
   ↓
5. Frontend: Store token in localStorage
   ↓
6. Axios interceptor adds: Authorization: Bearer {token}
   ↓
7. All future requests include token
   ↓
8. Logout: POST /api/auth/logout + Clear localStorage
```

## ✨ Points d'Extension

### Pages à créer
- [ ] `src/pages/EtudiantsPage.jsx`
- [ ] `src/pages/ProjetsPage.jsx`
- [ ] `src/pages/ImagesPage.jsx`
- [ ] `src/pages/QuizPage.jsx`
- [ ] `src/pages/NotesPage.jsx`

### Composants à créer
- [ ] `src/components/Table.jsx`
- [ ] `src/components/Modal.jsx`
- [ ] `src/components/Form.jsx`
- [ ] `src/components/Pagination.jsx`
- [ ] `src/components/Tooltip.jsx`

### Services à créer
- [ ] Plus de détails dans `services/index.js`

---

**Dernière mise à jour**: Mai 2026
