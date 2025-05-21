import { Link } from '@inertiajs/react';

export default function NavigationMenu({ url, active, title }) {
    return (
        <Link
            href={url}
            className={`block rounded-lg px-3 py-2 text-white ${active ? 'bg-blue-800' : 'hover:bg-blue-800'}`}
        >
            {title}
        </Link>
    );
}
