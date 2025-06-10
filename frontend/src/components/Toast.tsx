import { useEffect, useState } from 'react';
import {
    CheckCircleIcon,
    ExclamationCircleIcon,
    InformationCircleIcon,
} from '@heroicons/react/24/solid';

type ToastType = 'success' | 'error' | 'info';

export default function Toast({ message, type = 'success' }: {
    message: string;
    type?: ToastType;
}) {
    const [visible, setVisible] = useState(true);

    useEffect(() => {
        const timeout = setTimeout(() => setVisible(false), 3000);
        return () => clearTimeout(timeout);
    }, []);

    if (!visible) return null;

    const colorMap: Record<ToastType, string> = {
        success: 'bg-green-600',
        error: 'bg-red-600',
        info: 'bg-blue-600',
    };

    const iconMap: Record<ToastType, JSX.Element> = {
        success: <CheckCircleIcon className="w-5 h-5 text-white" />,
        error: <ExclamationCircleIcon className="w-5 h-5 text-white" />,
        info: <InformationCircleIcon className="w-5 h-5 text-white" />,
    };

    return (
        <div className={`fixed top-4 right-4 z-50 text-white px-4 py-3 rounded shadow-lg animate-fade-in-out flex items-center gap-2 ${colorMap[type]}`}>
            {iconMap[type]}
            <span>{message}</span>
        </div>
    );
}
