import { buttonVariants } from '@/Components/ui/button';
import { cn } from '@/lib/utils';
import { ChevronLeftIcon, ChevronRightIcon, DoubleArrowLeftIcon, DoubleArrowRightIcon } from '@radix-ui/react-icons';
import * as React from 'react';

const Pagination = ({ className, ...props }) => (
    <nav
        role="navigation"
        aria-label="pagination"
        className={cn('mx-auto flex w-full justify-center', className)}
        {...props}
    />
);
Pagination.displayName = 'Pagination';

const PaginationContent = React.forwardRef(({ className, ...props }, ref) => (
    <ul ref={ref} className={cn('flex flex-row items-center gap-1', className)} {...props} />
));
PaginationContent.displayName = 'PaginationContent';

const PaginationItem = React.forwardRef(({ className, ...props }, ref) => (
    <li ref={ref} className={cn('', className)} {...props} />
));
PaginationItem.displayName = 'PaginationItem';

const PaginationLink = ({ className, isActive, size = 'icon', ...props }) => (
    <a
        aria-current={isActive ? 'page' : undefined}
        className={cn(
            buttonVariants({
                variant: isActive ? 'outline' : 'ghost',
                size,
            }),
            className,
        )}
        {...props}
    />
);
PaginationLink.displayName = 'PaginationLink';

const PaginationPrevious = ({ className, ...props }) => (
    <PaginationItem>
        <PaginationLink
            aria-label="Go to previous page"
            size="default"
            className={cn('gap-1 pl-2.5', className)}
            {...props}
        >
            <ChevronLeftIcon className="h-4 w-4" />
            <span>Previous</span>
        </PaginationLink>
    </PaginationItem>
);
PaginationPrevious.displayName = 'PaginationPrevious';

const PaginationNext = ({ className, ...props }) => (
    <PaginationItem>
        <PaginationLink
            aria-label="Go to next page"
            size="default"
            className={cn('gap-1 pr-2.5', className)}
            {...props}
        >
            <span>Next</span>
            <ChevronRightIcon className="h-4 w-4" />
        </PaginationLink>
    </PaginationItem>
);
PaginationNext.displayName = 'PaginationNext';

const PaginationFirst = ({ className, ...props }) => (
    <PaginationItem>
        <PaginationLink aria-label="Go to first page" size="icon" className={cn('', className)} {...props}>
            <DoubleArrowLeftIcon className="h-4 w-4" />
        </PaginationLink>
    </PaginationItem>
);
PaginationFirst.displayName = 'PaginationFirst';

const PaginationLast = ({ className, ...props }) => (
    <PaginationItem>
        <PaginationLink aria-label="Go to last page" size="icon" className={cn('', className)} {...props}>
            <DoubleArrowRightIcon className="h-4 w-4" />
        </PaginationLink>
    </PaginationItem>
);
PaginationLast.displayName = 'PaginationLast';

const PaginationEllipsis = ({ className, ...props }) => (
    <span aria-hidden className={cn('flex h-9 w-9 items-center justify-center', className)} {...props}>
        ...
    </span>
);
PaginationEllipsis.displayName = 'PaginationEllipsis';

export {
    Pagination,
    PaginationContent,
    PaginationEllipsis,
    PaginationFirst,
    PaginationItem,
    PaginationLast,
    PaginationLink,
    PaginationNext,
    PaginationPrevious,
};
