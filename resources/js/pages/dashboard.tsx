import AppLayout from '@/components/layouts/app-layout';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/react';

interface Props {
    stats: {
        total_users: number;
        verified_users: number;
        total_kader: number;
        total_pengurus: number;
        total_administrator: number;
        total_books: number;
        total_works: number;
        active_loans: number;
        pending_loans: number;
        overdue_loans: number;
        recent_attendances: Array<{
            id: number;
            title: string;
            date: string;
            user: {
                name: string;
            };
        }>;
    };
    recentLoans: Array<{
        id: number;
        user: {
            name: string;
        };
        book: {
            title: string;
        };
        status: string;
        created_at: string;
    }>;
    recentWorks: Array<{
        id: number;
        title: string;
        type: string;
        user: {
            name: string;
        };
        created_at: string;
    }>;
    [key: string]: unknown;
}

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
];

export default function Dashboard({ stats, recentLoans, recentWorks }: Props) {
    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Dashboard - SIK HMI Hukum Brawijaya" />
            
            <div className="space-y-6">
                {/* Welcome Section */}
                <div className="bg-gradient-to-r from-[#065B2C] to-[#0a7a38] rounded-xl p-6 text-white">
                    <h1 className="text-2xl font-bold mb-2">📊 Dashboard SIK HMI Hukum Brawijaya</h1>
                    <p className="opacity-90">Selamat datang di Sistem Informasi Kader</p>
                </div>

                {/* Stats Grid */}
                <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <div className="bg-white rounded-lg border p-6">
                        <div className="flex items-center justify-between">
                            <div>
                                <p className="text-sm font-medium text-gray-600">Total Kader</p>
                                <p className="text-3xl font-bold text-[#065B2C]">{stats.total_kader}</p>
                            </div>
                            <div className="text-2xl">👥</div>
                        </div>
                        <p className="text-xs text-gray-500 mt-2">
                            {stats.verified_users} terverifikasi
                        </p>
                    </div>

                    <div className="bg-white rounded-lg border p-6">
                        <div className="flex items-center justify-between">
                            <div>
                                <p className="text-sm font-medium text-gray-600">Total Buku</p>
                                <p className="text-3xl font-bold text-[#065B2C]">{stats.total_books}</p>
                            </div>
                            <div className="text-2xl">📚</div>
                        </div>
                        <p className="text-xs text-gray-500 mt-2">
                            {stats.active_loans} sedang dipinjam
                        </p>
                    </div>

                    <div className="bg-white rounded-lg border p-6">
                        <div className="flex items-center justify-between">
                            <div>
                                <p className="text-sm font-medium text-gray-600">Karya Kader</p>
                                <p className="text-3xl font-bold text-[#065B2C]">{stats.total_works}</p>
                            </div>
                            <div className="text-2xl">✍️</div>
                        </div>
                        <p className="text-xs text-gray-500 mt-2">
                            Artikel, Esai, dan KTI
                        </p>
                    </div>

                    <div className="bg-white rounded-lg border p-6">
                        <div className="flex items-center justify-between">
                            <div>
                                <p className="text-sm font-medium text-gray-600">Peminjaman</p>
                                <p className="text-3xl font-bold text-[#065B2C]">{stats.pending_loans}</p>
                            </div>
                            <div className="text-2xl">⏳</div>
                        </div>
                        <p className="text-xs text-gray-500 mt-2">
                            {stats.overdue_loans} terlambat
                        </p>
                    </div>
                </div>

                {/* User Level Stats */}
                <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div className="bg-white rounded-lg border p-6">
                        <h3 className="font-semibold text-gray-900 mb-4">👑 Administrator</h3>
                        <p className="text-2xl font-bold text-[#065B2C]">{stats.total_administrator}</p>
                    </div>
                    
                    <div className="bg-white rounded-lg border p-6">
                        <h3 className="font-semibold text-gray-900 mb-4">🏛️ Pengurus</h3>
                        <p className="text-2xl font-bold text-[#065B2C]">{stats.total_pengurus}</p>
                    </div>
                    
                    <div className="bg-white rounded-lg border p-6">
                        <h3 className="font-semibold text-gray-900 mb-4">🎓 Kader</h3>
                        <p className="text-2xl font-bold text-[#065B2C]">{stats.total_kader}</p>
                    </div>
                </div>

                {/* Recent Activities */}
                <div className="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    {/* Recent Loans */}
                    <div className="bg-white rounded-lg border">
                        <div className="p-6 border-b">
                            <h3 className="font-semibold text-gray-900">📖 Peminjaman Terbaru</h3>
                        </div>
                        <div className="p-6 space-y-4">
                            {recentLoans.length > 0 ? (
                                recentLoans.map((loan) => (
                                    <div key={loan.id} className="flex items-center justify-between py-2">
                                        <div>
                                            <p className="font-medium text-sm">{loan.book.title}</p>
                                            <p className="text-xs text-gray-500">oleh {loan.user.name}</p>
                                        </div>
                                        <span className={`text-xs px-2 py-1 rounded-full ${
                                            loan.status === 'borrowed' ? 'bg-yellow-100 text-yellow-800' :
                                            loan.status === 'returned' ? 'bg-green-100 text-green-800' :
                                            'bg-blue-100 text-blue-800'
                                        }`}>
                                            {loan.status === 'borrowed' ? 'Dipinjam' :
                                             loan.status === 'returned' ? 'Dikembalikan' :
                                             loan.status === 'pending' ? 'Menunggu' : loan.status}
                                        </span>
                                    </div>
                                ))
                            ) : (
                                <p className="text-gray-500 text-sm">Belum ada peminjaman</p>
                            )}
                        </div>
                    </div>

                    {/* Recent Works */}
                    <div className="bg-white rounded-lg border">
                        <div className="p-6 border-b">
                            <h3 className="font-semibold text-gray-900">✍️ Karya Terbaru</h3>
                        </div>
                        <div className="p-6 space-y-4">
                            {recentWorks.length > 0 ? (
                                recentWorks.map((work) => (
                                    <div key={work.id} className="flex items-center justify-between py-2">
                                        <div>
                                            <p className="font-medium text-sm">{work.title}</p>
                                            <p className="text-xs text-gray-500">oleh {work.user.name}</p>
                                        </div>
                                        <span className="text-xs bg-[#065B2C] text-white px-2 py-1 rounded">
                                            {work.type === 'artikel' ? 'Artikel' :
                                             work.type === 'esai' ? 'Esai' : 'KTI'}
                                        </span>
                                    </div>
                                ))
                            ) : (
                                <p className="text-gray-500 text-sm">Belum ada karya</p>
                            )}
                        </div>
                    </div>
                </div>

                {/* Recent Attendances */}
                {stats.recent_attendances.length > 0 && (
                    <div className="bg-white rounded-lg border">
                        <div className="p-6 border-b">
                            <h3 className="font-semibold text-gray-900">✅ Aktivitas Absensi Terbaru</h3>
                        </div>
                        <div className="p-6 space-y-4">
                            {stats.recent_attendances.map((attendance) => (
                                <div key={attendance.id} className="flex items-center justify-between py-2">
                                    <div>
                                        <p className="font-medium text-sm">{attendance.title}</p>
                                        <p className="text-xs text-gray-500">{attendance.user.name}</p>
                                    </div>
                                    <p className="text-xs text-gray-500">
                                        {new Date(attendance.date).toLocaleDateString('id-ID')}
                                    </p>
                                </div>
                            ))}
                        </div>
                    </div>
                )}

                {/* Quote */}
                <div className="bg-gradient-to-r from-[#065B2C] to-[#0a7a38] rounded-xl p-6 text-white text-center">
                    <blockquote className="text-lg italic mb-2">
                        "Dan bahwasanya seorang manusia tiada memperoleh selain apa yang telah diusahakannya"
                    </blockquote>
                    <cite className="text-green-200 text-sm">— QS. An-Najm: 39</cite>
                </div>
            </div>
        </AppLayout>
    );
}