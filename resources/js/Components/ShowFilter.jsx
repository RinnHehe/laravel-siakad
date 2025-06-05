import { Badge } from '@/Components/ui/badge';
import { Separator } from '@/Components/ui/separator';
import { cn } from '@/lib/utils';
import { IconFilter } from '@tabler/icons-react';

export default function ShowFilter({ params, className = '' }) {
    const hasFilters = Object.keys(params).some((key) => params[key]);
    const currentPage = params?.page || 1;
    const currentLoad = params?.load || 2;

    return (
        <div>
            {hasFilters && (
                <div className={cn('flex w-full flex-wrap gap-y-2 bg-secondary p-3', className)}>
                    <span className="flex items-center gap-1 text-sm">
                        <IconFilter className="size-4" />
                        Filters:
                    </span>

                    <Separator orientation="vertical" className="mx-2 h-6" />

                    <Badge variant="white" className="mr-2">
                        Page: {currentPage}
                    </Badge>

                    {params?.search && (
                        <Badge variant="white" className="mr-2">
                            Search: {params.search}
                        </Badge>
                    )}

                    <Badge variant="white" className="mr-2">
                        Load: {currentLoad}
                    </Badge>
                </div>
            )}
        </div>
    );
}
