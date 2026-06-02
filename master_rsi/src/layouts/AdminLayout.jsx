import { Sidebar } from '../components/Sidebar'
import { Header } from '../components/Header'
import './AdminLayout.css'

export function AdminLayout({ children }) {
  return (
    <div className="admin-layout">
      <Sidebar />
      <div className="admin-main">
        <Header />
        <main className="admin-content">
          {children}
        </main>
      </div>
    </div>
  )
}
