import { useEffect, useState } from 'react';
import axios from '../api/axios';

export default function Me() {
    const [user, setUser] = useState<any>(null);

    useEffect(() => {
        axios.get('/users/me')
            .then(res => setUser(res.data))
            .catch(() => window.location.href = '/login');
    }, []);

    if (!user) return <p>Loading...</p>;

    return (
        <div className="p-4">
            <h1 className="text-xl font-bold">Hello, {user.name}</h1>
            <p>Email: {user.email}</p>
            <p>ID: {user.id}</p>
        </div>
    );
}
