// @ts-ignore
import {ReactNode, useEffect, useState} from 'react';
import Sidebar from './Sidebar';
import Topbar from './Topbar';
import Footer from './Footer';
import Breadcrumbs from '../components/Breadcrumbs';

export default function MainLayout({ children }: { children: ReactNode }) {
    const [sidebarOpen, setSidebarOpen] = useState(() =>
        localStorage.getItem('sidebar') !== 'closed'
    );
    const [dark, setDark] = useState(false);

    useEffect(() => {
        const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
        setDark(prefersDark);
    }, []);

    useEffect(() => {
        document.documentElement.classList.toggle('dark', dark);
    }, [dark]);

    return (
        <div className="flex bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100 transition-theme duration-300 min-h-screen flex-col">
            <Topbar
                toggleSidebar={() => {
                    const newState = !sidebarOpen;
                    setSidebarOpen(newState);
                    localStorage.setItem('sidebar', newState ? 'open' : 'closed');
                }}
                toggleTheme={() => setDark(!dark)}
                dark={dark}
            />
            <div className="flex flex-1">
                <Sidebar isOpen={sidebarOpen} onClose={() => setSidebarOpen(false)} />
                <main className="flex-1 pt-16 px-6 flex flex-col">
                    {/* Global Breadcrumbs here */}
                    <div className="mb-4">
                        <Breadcrumbs />
                    </div>
                    <div className="flex-1">
                        {children}
                    </div>
                    <Footer />
                </main>
            </div>
        </div>
    );
}
