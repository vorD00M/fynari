import { useNavigate } from 'react-router-dom';
import {
    PuzzlePieceIcon,
    UserGroupIcon,
    Cog6ToothIcon,
} from '@heroicons/react/24/outline';

const iconClass = 'w-6 h-6';

export default function Settings() {
    const navigate = useNavigate();

    const items = [
        {
            title: 'Управление модулями',
            description: 'Редактировать, включать/отключать модули',
            to: '/settings/modules',
            icon: <PuzzlePieceIcon className={iconClass} />,
            color: 'bg-blue-600',
        },
        {
            title: 'Пользователи',
            description: 'Просмотр и управление пользователями',
            to: '/settings/users',
            icon: <UserGroupIcon className={iconClass} />,
            color: 'bg-purple-600',
        },
        {
            title: 'Общие настройки',
            description: 'Настройки приложения, темы и параметры',
            to: '/settings/general',
            icon: <Cog6ToothIcon className={iconClass} />,
            color: 'bg-gray-600',
        },
    ];

    return (
        <div>
            <h1 className="text-2xl font-bold mb-6">⚙️ Панель настроек</h1>

            <div className="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                {items.map((item, i) => (
                    <button
                        key={i}
                        onClick={() => navigate(item.to)}
                        className={`rounded-lg p-6 text-left text-white shadow hover:shadow-md transition transform hover:-translate-y-1 ${item.color}`}
                    >
                        <div className="flex items-center gap-3 mb-2">
                            {item.icon}
                            <h2 className="text-lg font-semibold">{item.title}</h2>
                        </div>
                        <p className="text-sm opacity-80">{item.description}</p>
                    </button>
                ))}
            </div>
        </div>
    );
}
