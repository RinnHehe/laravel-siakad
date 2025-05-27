import { cn } from '@/lib/utils';
import { Card, CardHeader, CardTitle, CardContent } from '@/Components/ui/card';

export default function CardStat({ data, children }) {
    const { title, background, className = '', icon: Icon, iconClassName = '' } = data;

    return (
        <Card className={cn(background, className)}>
            <CardHeader className="flex flex-row items-center justify-between space-y-0 pb-2">
                <CardTitle className="text-sm font-medium">{title}</CardTitle>
                {Icon && <Icon className={cn('size-5', iconClassName)} />}
            </CardHeader>
            <CardContent>{children}</CardContent>
        </Card>
    );
}
