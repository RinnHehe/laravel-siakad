import CalendarSchedule from '@/Components/CalendarSchedule';
import HeaderTitle from '@/Components/HeaderTitle';
import StudentLayout from '@/Layouts/StudentLayout';
import { usePage } from '@inertiajs/react';
import { IconCalendar } from '@tabler/icons-react';

export default function Index(props) {
    // Get data directly from props instead of destructuring
    const { scheduleTable, days, debug } = props;
    const auth = usePage().props.auth.user;

    // Debug: log what we're receiving
    console.log('Index component props:', props);
    console.log('scheduleTable:', scheduleTable);
    console.log('days:', days);
    console.log('debug:', debug);

    return (
        <div className="flex w-full flex-col pb-32">
            <div className="mb-8 flex flex-col items-start justify-between gap-y-4 lg:flex-row lg:items-center">
                <HeaderTitle
                    title={props.page_settings.title}
                    subtitle={props.page_settings.subtitle}
                    icon={IconCalendar}
                />
            </div>

            <CalendarSchedule
                scheduleTable={scheduleTable || {}}
                days={days || []}
                student={auth}
                debug={debug}
            />
        </div>
    );
}

Index.layout = (page) => <StudentLayout children={page} />;