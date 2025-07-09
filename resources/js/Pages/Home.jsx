import { useEffect } from 'react';

const Navbar = () => (
    <nav className="flex items-center justify-between bg-blue-600 px-8 py-4 text-white">
        <div className="flex items-center space-x-3">
            <img src="/images/logo_poltekab.png" alt="Logo Poltekab" className="h-10 w-10 object-contain" />
            <span className="text-2xl font-bold tracking-wide">SIA Politeknik Kotabaru</span>
        </div>
    </nav>
);

const Hero = () => (
    <section id="home" className="bg-white px-4 py-20">
        <div className="mx-auto flex max-w-7xl flex-col items-center justify-between gap-8 md:flex-row">
            {/* Kiri: Judul dan deskripsi */}
            <div className="text-left md:w-1/2">
                <h1 className="mb-6 text-4xl font-extrabold leading-tight text-blue-900 md:text-5xl">
                    Sistem Informasi Akademik
                    <br />
                    Politeknik Kotabaru
                </h1>
                <p className="mb-8 text-lg text-gray-700">
                    Nikmati kemudahan sistem autentikasi tunggal untuk mengakses semua layanan dengan satu akun.
                </p>
                <div className="flex flex-col gap-4 sm:flex-row">
                    <a
                        href="/login"
                        className="rounded-md border-2 border-blue-900 px-8 py-3 text-center font-semibold text-blue-900 transition hover:bg-blue-900 hover:text-white"
                    >
                        Login
                    </a>
                </div>
            </div>
            {/* Kanan: Logo dan tulisan */}
            <div className="mt-10 flex flex-col items-center md:mt-0 md:w-1/2 md:items-start">
                <div className="flex items-center gap-6">
                    <img
                        src="/images/logo_poltekab.png"
                        alt="Logo Politeknik Kotabaru"
                        className="h-32 w-32 object-contain"
                    />
                    <span className="text-6xl font-bold tracking-wide text-black" style={{ fontFamily: 'sans-serif' }}>
                        POLITEKNIK KOTABARU
                    </span>
                </div>
            </div>
        </div>
    </section>
);

const About = () => (
    <section id="about" className="bg-white px-4 py-16 text-center">
        <h2 className="mb-4 text-3xl font-bold text-blue-900">Tentang SIA Poltekab</h2>
        <p className="mx-auto max-w-2xl text-gray-700">
            SIA Poltekab adalah sistem informasi akademik yang memudahkan mahasiswa dan dosen dalam mengelola data
            akademik, seperti KRS, nilai, jadwal kuliah, dan pengumuman kampus.
        </p>
    </section>
);

const Footer = () => (
    <footer className="bg-blue-600 py-4 text-center text-white">
        &copy; {new Date().getFullYear()} Politeknik Negeri Banjarmasin. All rights reserved.
    </footer>
);

export default function Home() {
    useEffect(() => {
        document.title = 'SIA Poltekab';
    }, []);

    return (
        <div>
            <Navbar />
            <Hero />
            <About />
            <Footer />
        </div>
    );
}
