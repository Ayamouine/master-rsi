import { useAuthStore } from '../store/authStore'
import './Header.css'

export function Header() {
  const user = useAuthStore((state) => state.user)

  return (
    <header className="header">
      <div className="header-content">
        <div className="header-title">
          <h1>Master RSI</h1>
          <p>Plateforme de Gestion des Projets</p>
        </div>
        <div className="header-user">
          {user && (
            <>
              <div className="user-avatar">{user.name.charAt(0)}</div>
              <div className="user-info">
                <p className="user-name">{user.name}</p>
                <p className="user-role">{user.type === 'admin' ? 'Administrateur' : 'Étudiant'}</p>
              </div>
            </>
          )}
        </div>
      </div>
    </header>
  )
}
