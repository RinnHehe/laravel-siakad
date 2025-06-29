import EmptyState from '@/Components/EmptyState';
import HeaderTitle from '@/Components/HeaderTitle';
import { Card, CardContent, CardHeader, CardTitle } from '@/Components/ui/card';
import AppLayout from '@/Layouts/AppLayout';
import { Link } from '@inertiajs/react';
import { IconDoor } from '@tabler/icons-react';

export default function Show(props) {
    return (
        <div className="flex w-full flex-col pb-32">
            <div className="mb-8 flex flex-col items-start justify-between gap-y-4 lg:flex-row lg:items-center">
                <HeaderTitle
                    title={props.page_settings.title}
                    subtitle={props.page_settings.subtitle}
                    icon={IconDoor}
                />
            </div>
            <Card>
                <CardHeader>
                    <CardTitle>Daftar Kelas</CardTitle>
                </CardHeader>
                <CardContent>
                    {props.course.schedules.length === 0 ? (
                        <EmptyState
                            icon={IconDoor}
                            title="Tidak ada kelas"
                            subtitle="Mulailah dengan membuat kelas baru"
                        />
                    ) : (
                        <ul role="list" className="grid grid-cols-1 gap-x-6 gap-y-8 lg:grid-cols-3">
                            {props.course.schedules.map((schedule, index) => (
                                <li key={index} className="overflow-hidden rounded-xl border">
                                    <Link
                                        href={route('teachers.classrooms.index', [schedule.course, schedule.classroom])}
                                        className="flex flex-col gap-x-4 bg-gray-50 p-6 hover:bg-blue-50"
                                    >
                                        <div className="text-lg font-bold leading-relaxed text-foreground">
                                            {schedule.classroom.name}
                                        </div>
                                        <div className="text-sm font-medium leading-relaxed text-muted-foreground">
                                            {schedule.faculty.name} - {schedule.department.name}
                                        </div>
                                    </Link>
                                </li>
                            ))}
                        </ul>
                    )}
                </CardContent>
            </Card>
        </div>
    );
}

Show.layout = (page) => <AppLayout title={page.props.page_settings.title} children={page} />;
