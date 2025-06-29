import CalendarSchedule from '@/Components/CalendarSchedule';
import HeaderTitle from '@/Components/HeaderTitle';
import { Alert, AlertDescription } from '@/Components/ui/alert';
import { Card, CardContent } from '@/Components/ui/card';
import AppLayout from '@/Layouts/AppLayout';
import { IconCalendar } from '@tabler/icons-react';

export default function Index(props) {
    const schedules = props.scheduleTable;
    const days = props.days;

    return (
        <div className="flex w-full flex-col pb-32">
            <div className="mb-8 flex flex-col items-start justify-between gap-y-4 lg:flex-row lg:items-center">
                <HeaderTitle
                    title={props.page_settings.title}
                    subtitle={props.page_settings.subtitle}
                    icon={IconCalendar}
                />
            </div>
            <Card>
                <CardContent className="p-4">
                    <CalendarSchedule days={days} scheduleTable={schedules}>
                        <div className="flex sm:hidden">
                            <Alert variant="destructive">
                                <AlertDescription>Jadwal hanya dapat dilihat di tampilan desktop</AlertDescription>
                            </Alert>
                        </div>
                    </CalendarSchedule>
                </CardContent>
            </Card>
        </div>
    );
}

Index.layout = (page) => <AppLayout title={page.props.page_settings.title} children={page} />;
