import { useEffect, useState } from 'react';
import axios from '../api/axios';
import { useToast } from '../context/ToastContext';
import * as HeroIcons from '@heroicons/react/24/outline';
import { iconGroups } from '../utils/iconGroups';
import {PencilIcon,
    CheckCircleIcon,
    PuzzlePieceIcon
} from '@heroicons/react/24/outline';

import Toggle from "../components/Toggle";

type Module = {
    id: number;
    code: string;
    name: string;
    description: string;
    type: string;
    icon: string;
    active: number;
    doc_prefix?: string;
    doc_scope?: string;
    show_in_menu: number;
};

export default function SettingsModules() {
    const [modules, setModules] = useState<Module[]>([]);
    const [selected, setSelected] = useState<Module | null>(null);
    const [iconSearch, setIconSearch] = useState('');
    const { showToast } = useToast();

    const fetchModules = () => {
        axios.get('/modules').then(res => setModules(res.data));
    };

    useEffect(() => {
        fetchModules();
    }, []);

    const updateModule = async () => {
        if (!selected) return;
        await axios.put(`/modules/${selected.id}`, selected);
        showToast('Модуль обновлён', 'success');
        fetchModules();
        setSelected(null);
        setIconSearch('');
    };

    return (
        <div>
            <h1 className="text-2xl font-bold mb-6">🧩 Управление модулями</h1>

            <div className="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                {modules.map((mod) => {
                    const Icon = HeroIcons[mod.icon as keyof typeof HeroIcons] || PuzzlePieceIcon;

                    return (
                        <div
                            key={mod.id}
                            className="bg-gray-50 dark:bg-gray-700 rounded shadow p-4 border dark:border-gray-600"
                        >
                            <div className="flex justify-between items-center mb-2">
                                <div className="flex items-center gap-2 font-bold text-lg text-gray-800 dark:text-white">
                                    <Icon className="w-5 h-5 text-blue-500" />
                                    {mod.name}
                                </div>
                                <span
                                    className={`text-xs font-semibold px-2 py-1 rounded ${
                                        mod.active
                                            ? 'bg-green-100 text-green-700 dark:bg-green-800 dark:text-green-100'
                                            : 'bg-red-100 text-red-700 dark:bg-red-800 dark:text-red-100'
                                    }`}
                                >
                  {mod.active ? 'Active' : 'Inactive'}
                </span>
                            </div>

                            <div className="text-sm text-gray-600 dark:text-gray-300 mb-2">
                                <div><strong>Code:</strong> {mod.code}</div>
                                <div><strong>Type:</strong> {mod.type}</div>
                            </div>

                            <button
                                onClick={() => setSelected({ ...mod })}
                                className="text-blue-600 dark:text-blue-400 text-sm inline-flex items-center gap-1 hover:underline"
                            >
                                <PencilIcon className="w-4 h-4" />
                                Edit
                            </button>
                        </div>
                    );
                })}
            </div>

            {selected && (
                <div className="fixed inset-0 bg-black/40 flex items-center justify-center z-50">
                    <div className="bg-white dark:bg-gray-900 p-6 rounded shadow w-full max-w-xl max-h-[90vh] overflow-y-auto">
                        <h3 className="text-lg font-bold mb-4">✏️ Редактировать модуль</h3>

                        <div className="space-y-4">
                            <input
                                className="w-full border p-2 rounded"
                                value={selected.name}
                                onChange={e => setSelected({...selected, name: e.target.value})}
                                placeholder="Название"
                            />
                            <input
                                className="w-full border p-2 rounded"
                                value={selected.description}
                                onChange={e => setSelected({...selected, description: e.target.value})}
                                placeholder="Описание"
                            />
                            <input
                                className="w-full border p-2 rounded bg-gray-100 text-gray-400"
                                value={selected.type}
                                readOnly
                            />

                            {selected.type === 'entity' && (
                                <>
                                    <input
                                        className="w-full border p-2 rounded"
                                        value={selected.doc_prefix || ''}
                                        onChange={e => setSelected({...selected, doc_prefix: e.target.value})}
                                        placeholder="Document Prefix"
                                    />
                                    <select
                                        className="w-full border p-2 rounded"
                                        value={selected.doc_scope || 'yearly'}
                                        onChange={e => setSelected({...selected, doc_scope: e.target.value})}
                                    >
                                        <option value="global">global</option>
                                        <option value="yearly">yearly</option>
                                        <option value="monthly">monthly</option>
                                        <option value="daily">daily</option>
                                    </select>
                                </>
                            )}

                            {/* Активен toggle */}
                            <div className="flex items-center justify-between">
                                <span className="text-sm">Активен</span>
                                <Toggle
                                    checked={!!selected.active}
                                    onChange={(val) => setSelected({...selected, active: val ? 1 : 0})}
                                />
                            </div>
                            {selected.type === 'entity' && (
                            <div className="flex items-center justify-between">
                                <span className="text-sm">Показать в меню</span>
                                <Toggle
                                    checked={!!selected.show_in_menu}
                                    onChange={(val) => setSelected({ ...selected, show_in_menu: val ? 1 : 0 })}
                                />
                            </div>
                            )}


                            {/* Иконка поиска и выбор */}
                            <div>
                                <label className="text-sm">Иконка</label>
                                <input
                                    type="text"
                                    value={iconSearch}
                                    onChange={e => setIconSearch(e.target.value)}
                                    placeholder="🔍 Поиск..."
                                    className="w-full border p-2 mb-2 rounded"
                                />

                                <div
                                    className="space-y-3 max-h-56 overflow-y-auto border p-2 rounded bg-white dark:bg-gray-800">
                                    {Object.entries(iconGroups).map(([group, icons]) => {
                                        const filtered = icons.filter(i =>
                                            i.name.toLowerCase().includes(iconSearch.toLowerCase())
                                        );
                                        if (filtered.length === 0) return null;

                                        return (
                                            <div key={group}>
                                                <div
                                                    className="text-xs font-bold mb-1 text-gray-500 dark:text-gray-400">{group}</div>
                                                <div className="grid grid-cols-5 gap-2">
                                                    {filtered.map(({name, icon: Icon}) => (
                                                        <button
                                                            key={name}
                                                            onClick={() => setSelected({...selected, icon: name})}
                                                            className={`flex flex-col items-center p-2 rounded hover:bg-gray-200 dark:hover:bg-gray-700 transition ${
                                                                selected.icon === name ? 'bg-blue-100 dark:bg-blue-900' : ''
                                                            }`}
                                                        >
                                                            <Icon className="w-5 h-5 text-gray-800 dark:text-gray-200"/>
                                                            <span
                                                                className="text-[10px] mt-1">{name.replace('Icon', '')}</span>
                                                        </button>
                                                    ))}
                                                </div>
                                            </div>
                                        );
                                    })}
                                </div>
                            </div>
                        </div>

                        <div className="mt-6 flex justify-end gap-2">
                            <button onClick={() => setSelected(null)} className="px-4 py-2 border rounded">
                                Отмена
                            </button>
                            <button
                                onClick={updateModule}
                                className="px-4 py-2 bg-blue-600 text-white rounded inline-flex items-center gap-2"
                            >
                                <CheckCircleIcon className="w-4 h-4" />
                                Сохранить
                            </button>
                        </div>
                    </div>
                </div>
            )}
        </div>
    );
}
