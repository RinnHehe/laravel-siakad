import { useEffect } from 'react';
import { IconHelpCircle, IconMessage2, IconBrandFacebook, IconBrandTwitter, IconBrandInstagram, IconMapPin, IconPhone, IconMail, IconFlagX } from '@tabler/icons-react';

const COLOR_PRIMARY = '#4077A0';

const scrollToSection = (id) => {
    const el = document.getElementById(id);
    if (el) el.scrollIntoView({ behavior: 'smooth' });
};

const Navbar = () => (
    <nav className="sticky top-0 z-50 flex items-center justify-between px-8 py-4 text-white shadow" style={{ backgroundColor: COLOR_PRIMARY }}>
        <div className="flex items-center space-x-3 cursor-pointer" onClick={() => scrollToSection('hero')}>
            <img src="/images/logo_poltekab.png" alt="Logo Politeknik Kotabaru" className="h-10 w-10 object-contain" />
            <span className="text-xl font-bold tracking-wide">SIA Politeknik Kotabaru</span>
        </div>
        <div className="hidden space-x-6 md:flex">
            <button onClick={() => scrollToSection('hero')} className="hover:text-gray-200">Beranda</button>
            <button onClick={() => scrollToSection('programs')} className="hover:text-gray-200">Program Studi</button>
            <button onClick={() => scrollToSection('about')} className="hover:text-gray-200">Tentang</button>
            <button onClick={() => scrollToSection('faq')} className="hover:text-gray-200">FAQ</button>
        </div>
    </nav>
);

const Hero = () => (
    <section id="hero" className="bg-blue-50 px-4 py-20">
        <div className="mx-auto flex max-w-6xl flex-col items-center justify-between gap-8 md:flex-row">
            <div className="text-center md:text-left md:w-1/2">
                <h1 className="mb-6 text-4xl font-extrabold leading-tight text-blue-900 md:text-5xl">
                    Sistem Informasi Akademik Politeknik Kotabaru
                </h1>
                <p className="mb-8 text-lg text-gray-700">
                    Nikmati kemudahan sistem autentikasi tunggal untuk mengakses semua layanan dengan satu akun.
                </p>
                <div className="flex flex-col items-center gap-4 sm:flex-row md:items-start">
                    <a href="/login" className="rounded bg-[#4077A0] px-6 py-3 text-white hover:bg-[#305a78] transition">Login</a>
                    <a href="https://poltekab.ac.id/#" target="_blank" rel="noopener noreferrer" className="rounded border-2 border-[#4077A0] px-6 py-3 text-[#4077A0] hover:bg-[#4077A0] hover:text-white transition">Kunjungi Website Politeknik Kotabaru</a>
                </div>
            </div>
            <div className="md:w-1/2 flex justify-center">
                <img src="/images/logo_poltekab.png" alt="Logo Politeknik Kotabaru" className="h-48 w-48 md:h-64 md:w-64 object-contain" />
            </div>
        </div>
    </section>
);

const Programs = () => (
    <section id="programs" className="bg-white px-4 py-16 text-center">
        <h2 className="mb-4 text-3xl font-bold text-blue-900">Program Studi</h2>
        <p className="mb-8 max-w-2xl mx-auto text-gray-700">
            Politeknik Kotabaru ingin menjadikan dirinya sebagai Rumah Ilmu yang selalu memiliki ciri khas mengedepankan keberanian yang bertanggung jawab, kebebasan yang didasari oleh kekuatan nalar yang kokoh serta keterbukaan dalam menerima segala informasi keilmuan yang diperlukan.
        </p>
        <div className="mx-auto grid max-w-4xl grid-cols-1 gap-6 sm:grid-cols-2 md:grid-cols-4">
            {['D3 Teknik Sipil', 'D3 Teknik Listrik', 'D3 Teknik Mesin', 'D3 Administrasi Bisnis'].map((program, idx) => (
                <div key={idx} className="rounded-lg border p-4 shadow hover:shadow-md transition flex flex-col justify-center items-center">
                    <h3 className="text-lg font-semibold text-[#4077A0] mb-2">{program}</h3>
                    <span className="text-sm text-gray-600">Akreditasi: Baik</span>
                </div>
            ))}
        </div>
    </section>
);


const About = () => (
    <section id="about" className="bg-blue-50 px-4 py-16 text-center">
        <h2 className="mb-4 text-3xl font-bold text-blue-900">Tentang SIA Politeknik Kotabaru</h2>
        <p className="mx-auto max-w-2xl text-gray-700">
            Sistem Informasi Akademik Politeknik Kotabaru mempermudah mahasiswa dan dosen dalam mengelola akademik seperti KRS, nilai, jadwal kuliah, dan pengumuman secara terpadu, akurat, dan cepat.
        </p>
    </section>
);

const FAQ = () => (
    <section id="faq" className="bg-white px-4 py-16 text-center">
        <h2 className="mb-4 text-3xl font-bold text-blue-900">Pertanyaan Yang Sering Ditanyakan</h2>
        <p className="mb-8 max-w-2xl mx-auto text-gray-700">
            Berikut adalah beberapa pertanyaan yang sering ditanyakan tentang Politeknik Kotabaru.
        </p>
        <div className="mx-auto max-w-2xl text-left space-y-6">
            {[
                {
                    question: "Di manakah alamat Politeknik Kotabaru?",
                    answer: "Politeknik Kotabaru beralamat lengkap di Jalan Raya Stagen Km 9,5, Kec. Pulau Laut Utara."
                },
                {
                    question: "Apa akreditasi Politeknik Kotabaru?",
                    answer: "Politeknik Kotabaru mendapatkan status akreditasi Baik dari badan akreditasi."
                },
                {
                    question: "Bagaimana cara menghubungi pihak Politeknik Kotabaru?",
                    answer: "Anda bisa menghubungi melalui telepon 0518-6076838."
                },
                {
                    question: "Berapa biaya masuk Politeknik Kotabaru?",
                    answer: "Untuk informasi biaya masuk Politeknik Kotabaru Anda bisa langsung menghubungi pihak kampus atau cek infonya di website poltekab.ac.id."
                }
            ].map((item, idx) => (
                <div key={idx} className="flex items-start gap-3 bg-blue-50 rounded-lg p-4 shadow hover:shadow-md transition">
                    <IconHelpCircle size={32} color="#4077A0" className="flex-shrink-0 mt-1" />
                    <div>
                        <h3 className="font-semibold text-[#4077A0] mb-1 flex items-center">
                            Q: {item.question}
                        </h3>
                        <div className="flex items-start gap-2">
                            <IconMessage2 size={20} color="#4077A0" className="flex-shrink-0 mt-1" />
                            <p className="text-gray-700 leading-relaxed">
                                A: {item.answer}
                            </p>
                        </div>
                    </div>
                </div>
            ))}
        </div>
    </section>
);


const Footer = () => (
    <footer className="px-6 py-10 text-gray-700" style={{ backgroundColor: '#cce0ec' }}>
        <div className="max-w-6xl mx-auto grid grid-cols-1 md:grid-cols-4 gap-8">
            <div>
                <h3 className="text-xl font-bold mb-2 text-gray-800">Politeknik Kotabaru</h3>
                <div className="flex space-x-3 mt-2">
                    <IconBrandFacebook size={24} />
                    <IconBrandTwitter size={24} />
                    <IconBrandInstagram size={24} />
                </div>
            </div>
            <div>
                <h4 className="font-semibold text-gray-800 mb-2">Links</h4>
                <ul className="space-y-1">
                    <li><a href="https://poltekab.ac.id" className="hover:underline">Website Poltekab</a></li>
                    <li><a href="https://simpadu.poltekab.ac.id" className="hover:underline">Simpadu Poltekab</a></li>


                </ul>
            </div>
            <div>
                <h4 className="font-semibold text-gray-800 mb-2"></h4>
            </div>
            <div className="space-y-4">
  <div className="flex items-start gap-3">
    <IconMapPin
      size={28}
      strokeWidth={2}
      color="#4077A0"
      className="flex-shrink-0 mt-1"
      aria-label="Address"
    />
    <span className="text-gray-800 leading-relaxed">
      Jl. Raya Stagen KM 9,5, RT 14, Stagen, Pulau Laut Utara, Kab. Kotabaru
    </span>
  </div>

  <div className="flex items-start gap-3">
    <IconPhone
      size={28}
      strokeWidth={2}
      color="#4077A0"
      className="flex-shrink-0 mt-1"
      aria-label="Phone"
    />
    <span className="text-gray-800">0518-770-8070</span>
  </div>

  <div className="flex items-start gap-3">
    <IconFlagX
      size={28}
      strokeWidth={2}
      color="#4077A0"
      className="flex-shrink-0 mt-1"
      aria-label="Fax"
    />
    <span className="text-gray-800">0518-21-858</span>
  </div>

  <div className="flex items-start gap-3">
    <IconMail
      size={28}
      strokeWidth={2}
      color="#4077A0"
      className="flex-shrink-0 mt-1"
      aria-label="Email"
    />
    <span className="text-gray-800">politeknik.kotabaru@poltekab.ac.id</span>
  </div>
</div>


        </div>
        <div className="mt-8 flex flex-col md:flex-row justify-between items-center text-sm">
            <span>&copy; {new Date().getFullYear()} Politeknik Kotabaru. All rights reserved.</span>
            <div className="flex space-x-4 mt-2 md:mt-0">
                <a href="#" className="hover:underline">Privacy Policy</a>
                <a href="#" className="hover:underline">Terms & Conditions</a>
            </div>
        </div>
    </footer>
);

export default function Home() {
    useEffect(() => {
        document.title = 'SIA Politeknik Kotabaru';
    }, []);

    return (
        <div>
            <Navbar />
            <Hero />
            <Programs />
            <About />
            <FAQ />
            <Footer />
        </div>
    );

}
