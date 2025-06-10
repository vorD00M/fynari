import { useLocation, Link } from 'react-router-dom';

export default function Breadcrumbs() {
    const location = useLocation();
    const parts = location.pathname.split('/').filter(Boolean);

    return (
        <nav className="text-sm text-gray-600 dark:text-gray-300 mb-4">
            <Link to="/" className="hover:underline">🏠 Home</Link>
            {parts.map((p, i) => {
                const path = '/' + parts.slice(0, i + 1).join('/');
                return (
                    <span key={i}>
            {' / '}
                        <Link to={path} className="hover:underline capitalize">{decodeURIComponent(p)}</Link>
          </span>
                );
            })}
        </nav>
    );
}
