import { Pagination, PaginationContent, PaginationItem, PaginationLink, PaginationPrevious, PaginationNext } from '@Components/ui/pagination';
import { cn } from '@lib/utils';

export default function PaginationTable({ meta, links }) {
  return (
    <Pagination>
      <PaginationContent className="flex flex-wrap justify-center lg:justify-end">
        <PaginationItem>
          <PaginationPrevious
            className={cn('mb-1', !links.prev && 'cursor-not-allowed')}
            href={links.prev}
          />
        </PaginationItem>
        
        {meta.links.slice(1, -1).map((link, index) => (
          <PaginationItem key={index} className="mx-1 mb-1 lb:mb-0">
            <PaginationLink href={link.url} isActive={link.active}>
              {link.label}
            </PaginationLink>
          </PaginationItem>
        ))}

        <PaginationItem>
          <PaginationNext
            className={cn('mb-1', !links.next && 'cursor-not-allowed')}
            href={links.next}
          />
        </PaginationItem>
      </PaginationContent>
    </Pagination>
  );
}
