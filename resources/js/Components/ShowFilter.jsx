import { cn } from "@lib/utils";
import { IconFilter } from "@tabler/icons-react";
import { Separator } from "@Components/ui/separator";
import { Badge } from "@Components/ui/badge";

export default function ShowFilter({ params, className = '' }) {
  return (
    <div>
      {Object.keys(params).some((key) => params[key]) && (
        <div className={cn('flex w-full flex-wrap gap-y-2 bg-secondary p-3', className)}>
          <span className="flex items-center gap-1 text-sm">
            <IconFilter className="size-4" />
            Filters:
          </span>

          <Separator orientation="vertical" className="h-6 mx-2" />

          {Object.entries(params).map(([key, value], index) =>
            value && (
              <Badge key={index} variant="white" className="mr-2">
                {key.charAt(0).toUpperCase() + key.slice(1)}: {value}
              </Badge>
            )
          )}
        </div>
      )}
    </div>
  );
}
