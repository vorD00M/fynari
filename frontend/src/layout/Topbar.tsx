import { useEffect, useState } from 'react';
import axios from '../api/axios';
import {
    MoonIcon,
    SunIcon,
    ArrowLeftOnRectangleIcon
} from '@heroicons/react/24/outline';

export default function Topbar({
                                   toggleSidebar,
                                   toggleTheme,
                                   dark
                               }: {
    toggleSidebar: () => void;
    toggleTheme: () => void;
    dark: boolean;
}) {
    const [user, setUser] = useState<{ name: string } | null>(null);

    useEffect(() => {
        axios.get('/users/me')
            .then(res => setUser(res.data))
            .catch(() => {});
    }, []);

    const logout = () => {
        localStorage.removeItem('token');
        window.location.href = '/login';
    };

    return (
        <header className="fixed top-0 left-0 right-0 h-14 flex items-center justify-between bg-white dark:bg-gray-800 border-b px-4 z-50 transition-theme duration-300">
            <div className="flex items-center gap-4">
                <button onClick={toggleSidebar} className="lg:hidden text-gray-700 dark:text-white text-xl">
                    ☰
                </button>
                <span className="font-semibold text-gray-800 dark:text-white hidden sm:inline">📊 Fylari CRM</span>
            </div>

            <div className="flex items-center gap-4">
                <button onClick={toggleTheme} className="text-gray-600 dark:text-gray-300">
                    {dark ? (
                        <SunIcon className="w-5 h-5 text-yellow-500" />
                    ) : (
                        <MoonIcon className="w-5 h-5 text-gray-300" />
                    )}
                </button>

                <span className="text-sm text-gray-700 dark:text-gray-300">
          👋 {user?.name}
        </span>

                <button onClick={logout} className="text-red-500 hover:text-red-700">
                    <ArrowLeftOnRectangleIcon className="w-5 h-5" />
                </button>
            </div>
        </header>
    );
}
