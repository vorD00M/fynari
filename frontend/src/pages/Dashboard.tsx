import Breadcrumbs from '../components/Breadcrumbs';

const cards = [
    { title: 'Contacts', value: 128, icon: '📇', color: 'bg-blue-600' },
    { title: 'Companies', value: 42, icon: '🏢', color: 'bg-green-600' },
    { title: 'Users', value: 5, icon: '👥', color: 'bg-purple-600' },
];

export default function Dashboard() {
    return (
        <>
            <Breadcrumbs />
            <h1 className="text-2xl font-bold mb-6">📊 Dashboard</h1>
            <div className="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                {cards.map((c, i) => (
                    <div key={i} className={`p-4 rounded shadow text-white ${c.color}`}>
                        <div className="text-xl">{c.icon} {c.title}</div>
                        <div className="text-3xl font-bold mt-2">{c.value}</div>
                    </div>
                ))}
            </div>
        </>
    );
}
