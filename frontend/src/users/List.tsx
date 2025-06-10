import { useEffect, useState } from 'react';
import axios from '../api/axios';
import Toast from '../components/Toast';

type User = {
    id: number;
    name: string;
    email: string;
    is_admin: number;
    status: number;
    created_at: string;
};

export default function UserList() {
    const [users, setUsers] = useState<User[]>([]);
    const [filtered, setFiltered] = useState<User[]>([]);
    const [search, setSearch] = useState('');
    const [showModal, setShowModal] = useState(false);
    const [editUser, setEditUser] = useState<User | null>(null);
    const [toast, setToast] = useState('');

    const fetchUsers = () => {
        axios.get('/users')
            .then(res => {
                setUsers(res.data);
                setFiltered(res.data);
            });
    };

    useEffect(() => { fetchUsers(); }, []);
    useEffect(() => {
        setFiltered(
            users.filter(u =>
                u.name.toLowerCase().includes(search.toLowerCase()) ||
                u.email.toLowerCase().includes(search.toLowerCase())
            )
        );
    }, [search, users]);

    const toggleStatus = async (id: number, status: number) => {
        await axios.put(`/users/${id}`, { status: status ? 0 : 1 });
        fetchUsers();
        setToast(status ? 'User deactivated' : 'User activated');
    };

    return (
        <div>
            <div className="flex justify-between items-center mb-4">
                <h1 className="text-2xl font-bold">👥 Users</h1>
                <button
                    onClick={() => {
                        setEditUser(null);
                        setShowModal(true);
                    }}
                    className="bg-blue-600 text-white px-4 py-2 rounded"
                >
                    ➕ Add User
                </button>
            </div>

            <input
                type="text"
                className="mb-4 w-full border px-3 py-2 rounded"
                placeholder="Search by name or email..."
                value={search}
                onChange={e => setSearch(e.target.value)}
            />

            <table className="w-full bg-white shadow rounded border text-sm">
                <thead className="bg-gray-100">
                <tr>
                    <th className="p-3">#</th>
                    <th className="p-3">Name</th>
                    <th className="p-3">Email</th>
                    <th className="p-3">Admin</th>
                    <th className="p-3">Status</th>
                    <th className="p-3">Actions</th>
                </tr>
                </thead>
                <tbody>
                {filtered.map((u, i) => (
                    <tr key={u.id} className="border-t hover:bg-gray-50">
                        <td className="p-3">{i + 1}</td>
                        <td className="p-3">{u.name}</td>
                        <td className="p-3">{u.email}</td>
                        <td className="p-3">{u.is_admin ? 'Yes' : 'No'}</td>
                        <td className="p-3">
                            {u.status === 1
                                ? <span className="text-green-600">Active</span>
                                : <span className="text-red-500">Inactive</span>}
                        </td>
                        <td className="p-3 flex gap-2">
                            <button
                                onClick={() => {
                                    setEditUser(u);
                                    setShowModal(true);
                                }}
                                className="text-blue-600 hover:underline"
                            >
                                Edit
                            </button>
                            <button
                                onClick={() => toggleStatus(u.id, u.status)}
                                className="text-yellow-600 hover:underline"
                            >
                                {u.status ? 'Deactivate' : 'Activate'}
                            </button>
                        </td>
                    </tr>
                ))}
                </tbody>
            </table>

            {showModal && (
                <AddUserModal
                    user={editUser}
                    onClose={() => setShowModal(false)}
                    onSaved={() => {
                        fetchUsers();
                        setToast(editUser ? 'User updated' : 'User created');
                    }}
                />
            )}

            {toast && <Toast message={toast} />}
        </div>
    );
}

function AddUserModal({
                          user,
                          onClose,
                          onSaved
                      }: {
    user: User | null;
    onClose: () => void;
    onSaved: () => void;
}) {
    const [name, setName] = useState(user?.name || '');
    const [email, setEmail] = useState(user?.email || '');
    const [password, setPassword] = useState('');
    const [isAdmin, setIsAdmin] = useState(user?.is_admin === 1);

    const submit = async () => {
        try {
            if (user) {
                await axios.put(`/users/${user.id}`, {
                    name,
                    email,
                    is_admin: isAdmin ? 1 : 0,
                    ...(password && { password })
                });
            } else {
                await axios.post('/users', {
                    name,
                    email,
                    password,
                    is_admin: isAdmin ? 1 : 0
                });
            }
            onSaved();
            onClose();
        } catch (err: any) {
            alert(err.response?.data?.error || 'Error saving user');
        }
    };

    return (
        <div className="fixed inset-0 bg-black/40 flex items-center justify-center z-50">
            <div className="bg-white dark:bg-gray-800 rounded shadow p-6 w-full max-w-md">
                <h2 className="text-lg font-bold mb-4">
                    {user ? 'Edit User' : 'Add New User'}
                </h2>

                <div className="space-y-3">
                    <input
                        className="w-full border p-2 rounded"
                        placeholder="Name"
                        value={name}
                        onChange={e => setName(e.target.value)}
                    />
                    <input
                        className="w-full border p-2 rounded"
                        placeholder="Email"
                        value={email}
                        onChange={e => setEmail(e.target.value)}
                    />
                    <input
                        className="w-full border p-2 rounded"
                        placeholder="Password"
                        type="password"
                        value={password}
                        onChange={e => setPassword(e.target.value)}
                    />
                    <label className="flex items-center gap-2">
                        <input
                            type="checkbox"
                            checked={isAdmin}
                            onChange={e => setIsAdmin(e.target.checked)}
                        />
                        <span>Admin</span>
                    </label>
                </div>

                <div className="mt-6 flex justify-end gap-2">
                    <button onClick={onClose} className="px-4 py-2 border rounded">
                        Cancel
                    </button>
                    <button
                        onClick={submit}
                        className="px-4 py-2 bg-blue-600 text-white rounded"
                    >
                        Save
                    </button>
                </div>
            </div>
        </div>
    );
}
