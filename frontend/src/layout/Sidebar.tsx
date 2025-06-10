import { NavLink } from 'react-router-dom';
import { useState } from 'react';

export default function Sidebar({ isOpen, onClose }: { isOpen: boolean; onClose: () => void }) {
    const [openSection, setOpenSection] = useState('crm');

    const toggle = (section: string) => {
        setOpenSection((prev) => (prev === section ? '' : section));
    };

    return (
        <>
            {/* Overlay */}
            <div
                onClick={onClose}
                className={`lg:hidden fixed inset-0 bg-black/50 z-40 transition-opacity ${isOpen ? 'opacity-100' : 'opacity-0 pointer-events-none'}`}
            />

            {/* Sidebar */}
            <aside
                className={`fixed top-14 left-0 w-64 h-[calc(100vh-3.5rem)] bg-gray-900 text-white z-50 shadow-lg
        transform transition-transform duration-300 overflow-y-auto
        ${isOpen ? 'translate-x-0' : '-translate-x-full'} lg:translate-x-0 lg:static lg:block`}
            >
                <div className="p-4 font-bold border-b border-gray-700 text-center text-lg">
                    Navigation
                </div>

                {/* Collapsible Section 1 */}
                <div>
                    <button
                        onClick={() => toggle('crm')}
                        className="w-full text-left px-4 py-2 hover:bg-gray-700 font-semibold"
                    >
                        📇 CRM
                    </button>
                    <div className={`${openSection === 'crm' ? 'block' : 'hidden'} pl-4`}>
                        <NavLink to="/dashboard" className="block px-4 py-2 hover:bg-gray-700">📊 Dashboard</NavLink>
                        <NavLink to="/contacts" className="block px-4 py-2 hover:bg-gray-700">Contacts</NavLink>
                        <NavLink to="/companies" className="block px-4 py-2 hover:bg-gray-700">Companies</NavLink>
                    </div>
                </div>

                {/* Collapsible Section 2 */}
                <div>
                    <button
                        onClick={() => toggle('admin')}
                        className="w-full text-left px-4 py-2 hover:bg-gray-700 font-semibold"
                    >
                        👑 Admin
                    </button>
                    <div className={`${openSection === 'admin' ? 'block' : 'hidden'} pl-4`}>
                        <NavLink to="/users" className="block px-4 py-2 hover:bg-gray-700">Users</NavLink>
                        <NavLink to="/settings" className="block px-4 py-2 hover:bg-gray-700">⚙️ Settings</NavLink>
                        <NavLink to="/me" className="block px-4 py-2 hover:bg-gray-700">Profile</NavLink>
                    </div>
                </div>
            </aside>
        </>
    );
}
