import { useState } from 'react'
import { useLocation, Link } from 'react-router-dom'
import './Sidebar.css'

export function Sidebar() {
  const [isOpen, setIsOpen] = useState(true)
  const location = useLocation()

  const menuItems = [
    { path: '/', label: 'Accueil', icon: '🏠' },
    { path: '/dashboard', label: 'Dashboard', icon: '📊' },
    { path: '/etudiants', label: 'Étudiants', icon: '👥' },
    { path: '/projets', label: 'Projets', icon: '📁' },
    { path: '/quiz', label: 'Quiz', icon: '❓' },
    { path: '/images', label: 'Images', icon: '🖼️' },
    { path: '/notes', label: 'Notes', icon: '📝' },
    { path: '/about', label: 'À propos', icon: 'ℹ️' },
  ]

  return (
    <>
      {/* Bouton toggle */}
      <button
        onClick={() => setIsOpen(!isOpen)}
        className="sidebar-toggle"
        aria-label="Basculer la barre latérale"
      >
        ☰
      </button>

      {/* Sidebar */}
      <nav className={`sidebar ${isOpen ? 'open' : 'closed'}`}>
        <div className="sidebar-header">
          <h1 className="sidebar-logo">RSI</h1>
          {isOpen && <span className="sidebar-title">Master</span>}
        </div>

        <ul className="sidebar-menu">
          {menuItems.map((item) => (
            <li key={item.path}>
              <Link
                to={item.path}
                className={`sidebar-link ${location.pathname === item.path ? 'active' : ''}`}
                title={item.label}
              >
                <span className="sidebar-icon">{item.icon}</span>
                {isOpen && <span className="sidebar-label">{item.label}</span>}
              </Link>
            </li>
          ))}
        </ul>

        <div className="sidebar-footer">
          <button className="sidebar-logout" title="Déconnexion">
            🚪
          </button>
        </div>
      </nav>

      {/* Overlay pour mobile */}
      {isOpen && <div className="sidebar-overlay" onClick={() => setIsOpen(false)} />}
    </>
  )
}
