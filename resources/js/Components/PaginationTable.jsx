import { Pagination, PaginationContent, PaginationItem } from '@/Components/ui/pagination';
import { cn } from '@/lib/utils';
import { Link } from '@inertiajs/react';

export default function PaginationTable({ meta, links }) {
    return (
        <Pagination>
            <PaginationContent className="flex flex-wrap justify-center lg:justify-end">
                <PaginationItem>
                    <Link
                        href={links.prev}
                        className={cn(
                            'mb-1 inline-flex items-center rounded-md px-3 py-2 text-sm ring-offset-background transition-colors hover:bg-blue-600 hover:text-white focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50',
                            !links.prev && 'cursor-not-allowed opacity-50',
                        )}
                    >
                        Previous
                    </Link>
                </PaginationItem>

                {meta.links.slice(1, -1).map((link, index) => (
                    <PaginationItem key={index} className="lb:mb-0 mx-1 mb-1">
                        <Link
                            href={link.url}
                            className={cn(
                                'inline-flex items-center rounded-md px-3 py-2 text-sm ring-offset-background transition-colors hover:bg-blue-600 hover:text-white focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50',
                                link.active && 'bg-blue-600 text-white hover:bg-blue-700',
                            )}
                        >
                            {link.label}
                        </Link>
                    </PaginationItem>
                ))}

                <PaginationItem>
                    <Link
                        href={links.next}
                        className={cn(
                            'mb-1 inline-flex items-center rounded-md px-3 py-2 text-sm ring-offset-background transition-colors hover:bg-blue-600 hover:text-white focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50',
                            !links.next && 'cursor-not-allowed opacity-50',
                        )}
                    >
                        Next
                    </Link>
                </PaginationItem>
            </PaginationContent>
        </Pagination>
    );
}
