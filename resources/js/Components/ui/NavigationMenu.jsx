import { Link } from '@inertiajs/react';
import { cn } from '@lib/utils';

export default function NavigationMenu({ active = false, url = '#', title, ...props }) {
    return (
        <Link
            {...props}
            href={url}
            className={cn(
                active ? 'bg-blue-500 text-white' : 'text-white hover:bg-blue-500',
                'rounded-md px-3 py-2 text-base font-medium',
            )}
        >
            {title}
        </Link>
    );
}
