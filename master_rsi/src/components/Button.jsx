import PropTypes from 'prop-types'
import './Button.css'

export function Button({
  children,
  variant = 'primary',
  size = 'md',
  disabled = false,
  className = '',
  ...props
}) {
  const btnClass = `btn btn-${variant} btn-${size} ${className}`
  return (
    <button className={btnClass} disabled={disabled} {...props}>
      {children}
    </button>
  )
}

Button.propTypes = {
  children: PropTypes.node,
  variant: PropTypes.oneOf(['primary', 'secondary', 'danger', 'success', 'ghost', 'amber']),
  size: PropTypes.oneOf(['sm', 'md', 'lg']),
  disabled: PropTypes.bool,
  className: PropTypes.string,
}

ButtonGroup.propTypes = {
  children: PropTypes.node,
  className: PropTypes.string,
}

export function ButtonGroup({ children, className = '' }) {
  return (
    <div className={`button-group ${className}`}>
      {children}
    </div>
  )
}
