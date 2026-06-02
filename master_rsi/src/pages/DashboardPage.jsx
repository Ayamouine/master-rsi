import { useEffect, useState } from 'react'
import PropTypes from 'prop-types'
import { AdminLayout } from '../layouts/AdminLayout'
import { dashboardService } from '../services'
import './DashboardPage.css'

export function DashboardPage() {
  const [stats, setStats] = useState(null)
  const [loading, setLoading] = useState(true)

  useEffect(() => {
    const fetchStats = async () => {
      try {
        const data = await dashboardService.getStats()
        setStats(data)
      } catch (error) {
        console.error('Erreur:', error)
      } finally {
        setLoading(false)
      }
    }

    fetchStats()
  }, [])

  return (
    <AdminLayout>
      <div className="dashboard">
        <h2>Bienvenue dans le Dashboard</h2>

        {loading ? (
          <p>Chargement...</p>
        ) : (
          <div className="stats-grid">
            <StatCard icon="👥" label="Étudiants" value={stats?.etudiants || 0} />
            <StatCard icon="🖼️" label="Images" value={stats?.images || 0} />
            <StatCard icon="📁" label="Projets" value={stats?.projets || 0} />
          </div>
        )}
      </div>
    </AdminLayout>
  )
}

function StatCard({ icon, label, value }) {
  return (
    <div className="stat-card">
      <div className="stat-icon">{icon}</div>
      <div className="stat-content">
        <p className="stat-label">{label}</p>
        <p className="stat-value">{value}</p>
      </div>
    </div>
  )
}

StatCard.propTypes = {
  icon: PropTypes.string.isRequired,
  label: PropTypes.string.isRequired,
  value: PropTypes.number.isRequired,
}
