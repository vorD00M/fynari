import { createContext, useContext, useState, ReactNode } from 'react';
import Toast from '../components/Toast';

type ToastType = 'success' | 'error' | 'info';

type ToastContextType = {
    showToast: (msg: string, type?: ToastType) => void;
};

const ToastContext = createContext<ToastContextType | undefined>(undefined);

export const useToast = () => {
    const ctx = useContext(ToastContext);
    if (!ctx) throw new Error('useToast must be used inside <ToastProvider>');
    return ctx;
};

export function ToastProvider({ children }: { children: ReactNode }) {
    const [message, setMessage] = useState<string | null>(null);
    const [type, setType] = useState<ToastType>('success');
    const [key, setKey] = useState<number>(0);

    const showToast = (msg: string, type: ToastType = 'success') => {
        setMessage(msg);
        setType(type);
        setKey(Date.now());
    };

    return (
        <ToastContext.Provider value={{ showToast }}>
            {children}
            {message && <Toast key={key} message={message} type={type} />}
        </ToastContext.Provider>
    );
}
