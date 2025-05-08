import { Link } from '@inertiajs/react';

export default function Banner({ message }) {
    return (
        <div className="pointer-events-none fixed inset-x-0 bottom-0 sm:flex sm:justify-center sm:px-6 lg:px-8 lg:pb-5">
            <div className="pointer-events-none flex items-center justify-between gap-x-6 bg-gradient-to-r from-blue-400 via-blue-500 to-blue-400 px-6 py-2.5 sm:rounded-xl sm:py-3 sm:pl-4 sm:pr-3.5">
                <p className="text-sm leading-6 text-white">
                    <Link href="#">
                        <strong className="font-bold">Pengumuman</strong>
                        {message}
                    </Link>
                </p>
            </div>
        </div>
    );
}
