# 🚀 Guide de Démarrage - Master RSI

## 📋 Pré-requis

- **Node.js** ≥ 18.0
- **PHP** ≥ 8.2
- **Composer** ≥ 2.0
- **MySQL** ≥ 5.7 (ou autre SGBD)

## ⚙️ Installation Rapide

### Étape 1 : Configuration Backend (Laravel)

```bash
cd master_rsi

# Copier .env
cp .env.example .env

# Générer la clé de l'application
php artisan key:generate

# Installer les dépendances
composer install

# Créer la base de données (s'assurer que MySQL est en cours d'exécution)
php artisan migrate --force

# (Optionnel) Remplir avec des données de test
php artisan db:seed

# Lancer le serveur Laravel
php artisan serve
# ✅ Backend disponible sur http://localhost:8000
```

### Étape 2 : Configuration Frontend (React)

```bash
# Dans le même répertoire master_rsi

# Installer les dépendances Node
npm install

# Copier les variables d'environnement
cp .env.frontend .env.local

# Lancer le serveur Vite
npm run dev
# ✅ Frontend disponible sur http://localhost:5173
```

## ✅ Vérification de l'Installation

### 1. Backend fonctionne
```bash
curl http://localhost:8000/api/auth/user
# → Devrait retourner une erreur 401 (non authentifié) - C'est normal
```

### 2. Frontend se lance
- Ouvrir [http://localhost:5173](http://localhost:5173)
- Vous devriez voir la page de connexion

### 3. Commande de Test
```bash
# Terminal 1 - Backend
php artisan serve

# Terminal 2 - Frontend  
npm run dev

# Terminal 3 - Test
npm run test
```

## 📝 Variables d'Environnement

### Backend (.env)
```env
APP_NAME="Master RSI"
APP_DEBUG=true
APP_KEY=base64:YOUR_KEY_HERE
DB_DATABASE=master_rsi
DB_USERNAME=root
DB_PASSWORD=
SANCTUM_STATEFUL_DOMAINS=localhost:3000,localhost:5173
```

### Frontend (.env.local)
```env
VITE_API_URL=http://localhost:8000/api
```

## 🔌 Vérifier la Connexion API

### Depuis le terminal
```bash
# Vérifier que CORS est configuré
curl -H "Origin: http://localhost:5173" \
     -H "Access-Control-Request-Method: POST" \
     -H "Access-Control-Request-Headers: Content-Type" \
     -X OPTIONS http://localhost:8000/api/auth/login -v
```

### Depuis React
```javascript
// Dans un composant
import { authService } from '@/services'

const handleLogin = async () => {
  try {
    const result = await authService.login('email@test.com', 'password')
    console.log('Connecté!', result)
  } catch (error) {
    console.error('Erreur:', error)
  }
}
```

## 🎨 Utiliser le Design System

### Variables CSS disponibles
```css
/* Couleurs */
--primary-dark: #1a3a6b;        /* Bleu marine */
--primary-navy: #0d1b3e;        /* Navy */
--primary-indigo: #3730a3;      /* Indigo */
--primary-amber: #d97706;       /* Ambre */

/* Espaces */
--spacing-1 to --spacing-16

/* Fonts */
--font-display: 'Syne', sans-serif;
--font-mono: 'DM Mono', monospace;
--font-base: Système par défaut;

/* Rayon de bordure */
--radius-sm to --radius-2xl

/* Ombres */
--shadow-sm to --shadow-xl

/* Transitions */
--transition-fast: 150ms
--transition-base: 250ms
--transition-slow: 350ms
```

### Utilisation dans un composant
```jsx
import './MonComposant.css'

function MonComposant() {
  return (
    <div style={{
      background: 'var(--primary-dark)',
      padding: 'var(--spacing-4)',
      borderRadius: 'var(--radius-lg)',
      color: 'white'
    }}>
      Contenu
    </div>
  )
}
```

## 📦 Structure des Fichiers

```
master_rsi/
├── app/Http/Controllers/Api/          ← Contrôleurs API
├── routes/api.php                     ← Routes API
├── config/cors.php                    ← Configuration CORS
│
├── src/
│   ├── components/                    ← Composants réutilisables
│   ├── pages/                         ← Pages principales
│   ├── layouts/                       ← Layouts (AdminLayout, etc)
│   ├── services/                      ← Appels API (axios)
│   ├── store/                         ← État global (Zustand)
│   ├── styles/                        ← CSS global et design system
│   ├── hooks/                         ← Hooks React personnalisés
│   ├── App.jsx                        ← Composant principal
│   └── main.jsx                       ← Point d'entrée
│
├── index.html                         ← Template HTML
├── vite.config.js                     ← Configuration Vite
├── tailwind.config.js                 ← Configuration Tailwind
└── package.json                       ← Dépendances npm
```

## 🔐 Authentification API

### Flux complet
1. **Login**: POST `/api/auth/login` → reçoit un token
2. **Token stocké**: `localStorage.setItem('auth_token', token)`
3. **Requêtes suivantes**: Header `Authorization: Bearer {token}`
4. **Logout**: POST `/api/auth/logout` → vide le localStorage

### Implémenter une route protégée
```jsx
import { ProtectedRoute } from '@/components'

<ProtectedRoute>
  <MaPageProtegee />
</ProtectedRoute>
```

## 🛠️ Commandes Utiles

### Backend
```bash
php artisan make:model Nom -m          # Créer un modèle
php artisan make:controller NomController
php artisan migrate                    # Appliquer les migrations
php artisan migrate:rollback          # Annuler les migrations
php artisan tinker                    # Shell interactif
```

### Frontend
```bash
npm run dev                            # Démarrage
npm run build                          # Build production
npm run preview                        # Prévisualiser build
npm run lint                           # Vérifier le code
```

## 🚨 Problèmes Courants

### CORS Error
**Problem**: `Access to XMLHttpRequest blocked by CORS policy`

**Solution**:
1. Vérifier que Laravel tourne sur `localhost:8000`
2. Vérifier `config/cors.php` - ajouter votre domaine
3. Redémarrer Laravel: `php artisan serve`

### 401 Unauthorized
**Problem**: Erreur d'authentification persistante

**Solution**:
1. Vérifier le token: `localStorage.getItem('auth_token')`
2. Vérifier l'intercepteur dans `services/api.js`
3. Vérifier que Sanctum est configuré

### Module not found
**Problem**: Cannot find module '@/...'

**Solution**:
1. Vérifier `vite.config.js` - l'alias `@` est configuré?
2. Relancer `npm run dev`
3. Vider `node_modules` et réinstaller: `rm -rf node_modules && npm install`

## 📚 Prochaines Étapes

1. **Créer plus de pages**: Étudiants, Projets, Images, Quiz
2. **Ajouter des tests**: Jest + React Testing Library
3. **Setup CI/CD**: GitHub Actions ou similar
4. **Documentation API**: Swagger/OpenAPI
5. **Optimisation**: Lazy loading, code splitting

## 🤝 Support

Pour plus d'aide:
- Consulter [Laravel Docs](https://laravel.com/docs)
- Consulter [React Docs](https://react.dev)
- Vérifier le `README_REACT.md`

---

**Bonne chance! 🎉**
