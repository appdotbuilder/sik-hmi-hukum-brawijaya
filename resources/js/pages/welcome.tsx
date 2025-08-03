import { type SharedData } from '@/types';
import { Head, Link, usePage } from '@inertiajs/react';

interface Props {
    stats: {
        total_kader: number;
        total_books: number;
        total_works: number;
        recent_activities: Array<{
            id: number;
            title: string;
            date: string;
            user: {
                name: string;
            };
        }>;
    };
    featuredBooks: Array<{
        id: number;
        title: string;
        author: string;
        digital_url?: string;
    }>;
    featuredWorks: Array<{
        id: number;
        title: string;
        type: string;
        user: {
            name: string;
        };
        digital_url?: string;
    }>;
    [key: string]: unknown;
}

export default function Welcome({ stats, featuredBooks, featuredWorks }: Props) {
    const { auth } = usePage<SharedData>().props;
    const currentYear = new Date().getFullYear();

    return (
        <>
            <Head title="SIK HMI Hukum Brawijaya">
                <link rel="preconnect" href="https://fonts.bunny.net" />
                <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
            </Head>
            <div className="min-h-screen bg-gradient-to-br from-[#065B2C] to-[#0a7a38]">
                {/* Header */}
                <header className="relative z-10 px-6 py-4">
                    <nav className="flex items-center justify-between max-w-7xl mx-auto">
                        <div className="flex items-center space-x-3">
                            <div className="w-10 h-10 bg-white rounded-lg flex items-center justify-center">
                                <span className="text-[#065B2C] font-bold text-xl">📚</span>
                            </div>
                            <div>
                                <h1 className="text-white font-bold text-xl">SIK HMI</h1>
                                <p className="text-green-100 text-sm">Hukum Brawijaya</p>
                            </div>
                        </div>
                        
                        <div className="flex items-center space-x-4">
                            {auth.user ? (
                                <Link
                                    href={route('dashboard')}
                                    className="bg-white text-[#065B2C] px-6 py-2 rounded-lg font-semibold hover:bg-green-50 transition-colors"
                                >
                                    Dashboard
                                </Link>
                            ) : (
                                <>
                                    <Link
                                        href={route('login')}
                                        className="text-white hover:text-green-100 px-4 py-2 transition-colors"
                                    >
                                        Masuk
                                    </Link>
                                    <Link
                                        href={route('register')}
                                        className="bg-white text-[#065B2C] px-6 py-2 rounded-lg font-semibold hover:bg-green-50 transition-colors"
                                    >
                                        Daftar
                                    </Link>
                                </>
                            )}
                        </div>
                    </nav>
                </header>

                {/* Hero Section */}
                <div className="px-6 py-16">
                    <div className="max-w-7xl mx-auto text-center">
                        <h1 className="text-4xl md:text-6xl font-bold text-white mb-6">
                            📊 Sistem Informasi Kader
                        </h1>
                        <p className="text-xl md:text-2xl text-green-100 mb-8 max-w-3xl mx-auto">
                            Platform digital terintegrasi untuk manajemen keanggotaan, perpustakaan, dan absensi HMI Hukum Brawijaya
                        </p>
                        
                        {/* Stats */}
                        <div className="grid grid-cols-1 md:grid-cols-3 gap-6 max-w-4xl mx-auto mb-12">
                            <div className="bg-white/10 backdrop-blur-sm rounded-xl p-6 text-white">
                                <div className="text-3xl font-bold">{stats.total_kader}</div>
                                <div className="text-green-100">Kader Aktif</div>
                            </div>
                            <div className="bg-white/10 backdrop-blur-sm rounded-xl p-6 text-white">
                                <div className="text-3xl font-bold">{stats.total_books}</div>
                                <div className="text-green-100">Buku Tersedia</div>
                            </div>
                            <div className="bg-white/10 backdrop-blur-sm rounded-xl p-6 text-white">
                                <div className="text-3xl font-bold">{stats.total_works}</div>
                                <div className="text-green-100">Karya Kader</div>
                            </div>
                        </div>
                    </div>
                </div>

                {/* Features Section */}
                <div className="bg-white">
                    <div className="max-w-7xl mx-auto px-6 py-16">
                        <div className="text-center mb-12">
                            <h2 className="text-3xl font-bold text-gray-900 mb-4">🚀 Fitur Utama</h2>
                            <p className="text-xl text-gray-600">Kelola semua aspek keanggotaan dalam satu platform</p>
                        </div>
                        
                        <div className="grid grid-cols-1 md:grid-cols-3 gap-8">
                            <div className="text-center p-6">
                                <div className="w-16 h-16 bg-[#065B2C] rounded-full flex items-center justify-center mx-auto mb-4">
                                    <span className="text-white text-2xl">👥</span>
                                </div>
                                <h3 className="text-xl font-semibold mb-2">Manajemen Keanggotaan</h3>
                                <p className="text-gray-600">Kelola profil kader, verifikasi akun, dan sistem level pengguna yang terstruktur</p>
                            </div>
                            
                            <div className="text-center p-6">
                                <div className="w-16 h-16 bg-[#065B2C] rounded-full flex items-center justify-center mx-auto mb-4">
                                    <span className="text-white text-2xl">📚</span>
                                </div>
                                <h3 className="text-xl font-semibold mb-2">Perpustakaan Digital</h3>
                                <p className="text-gray-600">Akses buku dan karya kader, sistem peminjaman online, dan koleksi digital</p>
                            </div>
                            
                            <div className="text-center p-6">
                                <div className="w-16 h-16 bg-[#065B2C] rounded-full flex items-center justify-center mx-auto mb-4">
                                    <span className="text-white text-2xl">✅</span>
                                </div>
                                <h3 className="text-xl font-semibold mb-2">Absensi Digital</h3>
                                <p className="text-gray-600">Presensi kegiatan bidang dan program kegiatan dengan sistem terintegrasi</p>
                            </div>
                        </div>
                    </div>
                </div>

                {/* Featured Content */}
                {(featuredBooks.length > 0 || featuredWorks.length > 0) && (
                    <div className="bg-gray-50">
                        <div className="max-w-7xl mx-auto px-6 py-16">
                            {featuredBooks.length > 0 && (
                                <div className="mb-12">
                                    <h2 className="text-2xl font-bold text-gray-900 mb-6">📖 Buku Terbaru</h2>
                                    <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
                                        {featuredBooks.slice(0, 3).map((book) => (
                                            <div key={book.id} className="bg-white rounded-lg p-6 shadow-sm border">
                                                <h3 className="font-semibold text-lg mb-2">{book.title}</h3>
                                                <p className="text-gray-600 mb-4">oleh {book.author}</p>
                                                {book.digital_url && (
                                                    <a 
                                                        href={book.digital_url} 
                                                        target="_blank" 
                                                        rel="noopener noreferrer"
                                                        className="text-[#065B2C] hover:underline text-sm"
                                                    >
                                                        Akses Digital →
                                                    </a>
                                                )}
                                            </div>
                                        ))}
                                    </div>
                                </div>
                            )}

                            {featuredWorks.length > 0 && (
                                <div>
                                    <h2 className="text-2xl font-bold text-gray-900 mb-6">✍️ Karya Kader Terbaru</h2>
                                    <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
                                        {featuredWorks.slice(0, 3).map((work) => (
                                            <div key={work.id} className="bg-white rounded-lg p-6 shadow-sm border">
                                                <h3 className="font-semibold text-lg mb-2">{work.title}</h3>
                                                <p className="text-gray-600 mb-2">oleh {work.user.name}</p>
                                                <span className="text-sm bg-[#065B2C] text-white px-2 py-1 rounded">
                                                    {work.type === 'artikel' ? 'Artikel/Jurnal' : 
                                                     work.type === 'esai' ? 'Esai' : 'KTI'}
                                                </span>
                                                {work.digital_url && (
                                                    <div className="mt-4">
                                                        <a 
                                                            href={work.digital_url} 
                                                            target="_blank" 
                                                            rel="noopener noreferrer"
                                                            className="text-[#065B2C] hover:underline text-sm"
                                                        >
                                                            Baca Karya →
                                                        </a>
                                                    </div>
                                                )}
                                            </div>
                                        ))}
                                    </div>
                                </div>
                            )}
                        </div>
                    </div>
                )}

                {/* Quote Section */}
                <div className="bg-[#065B2C]">
                    <div className="max-w-4xl mx-auto px-6 py-16 text-center">
                        <blockquote className="text-xl md:text-2xl text-white italic mb-4">
                            "Dan bahwasanya seorang manusia tiada memperoleh selain apa yang telah diusahakannya"
                        </blockquote>
                        <cite className="text-green-200">— QS. An-Najm: 39</cite>
                    </div>
                </div>

                {/* Footer */}
                <footer className="bg-gray-900 text-white">
                    <div className="max-w-7xl mx-auto px-6 py-8">
                        <div className="text-center">
                            <p className="mb-2">Copyright HMI Hukum Brawijaya {currentYear}</p>
                            <p className="text-gray-400 text-sm">
                                Pengembangan Web didukung oleh Bidang Penelitian dan Pengembangan
                            </p>
                        </div>
                    </div>
                </footer>
            </div>
        </>
    );
}