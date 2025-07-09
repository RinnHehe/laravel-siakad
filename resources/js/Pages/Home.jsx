import React, { useEffect } from "react";

const Navbar = () => (
  <nav className="bg-blue-600 text-white px-8 py-4 flex justify-between items-center">
    <div className="flex items-center space-x-3">
      <img
        src="/images/logo_poltekab.png"
        alt="Logo Poltekab"
        className="h-10 w-10 object-contain"
      />
      <span className="font-bold text-2xl tracking-wide">SIA Politeknik Kotabaru</span>
    </div>
  </nav>
);

const Hero = () => (
  <section id="home" className="bg-white py-20 px-4">
    <div className="max-w-7xl mx-auto flex flex-col md:flex-row items-center justify-between gap-8">
      {/* Kiri: Judul dan deskripsi */}
      <div className="md:w-1/2 text-left">
        <h1 className="text-4xl md:text-5xl font-extrabold text-blue-900 mb-6 leading-tight">
          Sistem Informasi Akademik<br />
          Politeknik Kotabaru
        </h1>
        <p className="text-lg text-gray-700 mb-8">
        Nikmati kemudahan sistem autentikasi tunggal untuk mengakses semua layanan dengan satu akun.
        </p>
        <div className="flex flex-col sm:flex-row gap-4">
          <a
            href="/login"
            className="px-8 py-3 border-2 border-blue-900 text-blue-900 font-semibold rounded-md hover:bg-blue-900 hover:text-white transition text-center"
          >
            Login
          </a>
        </div>
      </div>
      {/* Kanan: Logo dan tulisan */}
      <div className="md:w-1/2 flex flex-col items-center md:items-start mt-10 md:mt-0">
        <div className="flex items-center gap-6">
          <img
            src="/images/logo_poltekab.png"
            alt="Logo Politeknik Kotabaru"
            className="h-32 w-32 object-contain"
          />
          <span className="text-6xl font-bold text-black tracking-wide" style={{ fontFamily: 'sans-serif' }}>
            POLITEKNIK KOTABARU
          </span>
        </div>
      </div>
    </div>
  </section>
);

const About = () => (
  <section id="about" className="py-16 px-4 bg-white text-center">
    <h2 className="text-3xl font-bold text-blue-900 mb-4">Tentang SIA Poltekab</h2>
    <p className="max-w-2xl mx-auto text-gray-700">
      SIA Poltekab adalah sistem informasi akademik yang memudahkan mahasiswa dan dosen dalam mengelola data akademik, seperti KRS, nilai, jadwal kuliah, dan pengumuman kampus.
    </p>
  </section>
);


const Footer = () => (
  <footer className="bg-blue-600 text-white text-center py-4">
    &copy; {new Date().getFullYear()} Politeknik Negeri Banjarmasin. All rights reserved.
  </footer>
);

export default function Home() {
  useEffect(() => {
    document.title = "SIA Poltekab";
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