import React from 'react';
import { Link } from '@inertiajs/react';

export default function NavigationMenu({ url, active, title }) {
    return (
        <Link
            href={url}
            className={`block py-2 px-3 text-white rounded-lg ${
                active ? 'bg-blue-800' : 'hover:bg-blue-800'
            }`}
        >
            {title}
        </Link>
    );
} 