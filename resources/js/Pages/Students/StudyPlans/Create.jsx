import HeaderTitle from '@/Components/HeaderTitle';
import { Button } from '@/Components/ui/button';
import { Checkbox } from '@/Components/ui/checkbox';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/Components/ui/table';
import StudentLayout from '@/Layouts/StudentLayout';
import { cn } from '@/lib/utils';
import { Link, useForm } from '@inertiajs/react';
import { IconArrowBack, IconBuilding, IconCheck } from '@tabler/icons-react';

export default function Create(props) {
    const { data, setData, post, errors, processing } = useForm({
        schedule_id: [],
        _method: props.page_settings.method,
    });

    const onHandleSubmit = (e) => {
        e.preventDefault();
        post(props.page_settings.action, {
            preserveScroll: true,
            preserveState: true,
            // onSuccess: (success) => {
            //     const flash = flashMessage(success);
            //     if(flash) toast[flash.type](flash.messgae)
            // }
        });
    };

    const onHandleReset = () => {
        ResetIcon();
    };

    return (
        <div className="flex w-full flex-col pb-32">
            <div className="mb-8 flex flex-col items-start justify-between gap-y-4 lg:flex-row lg:items-center">
                <HeaderTitle
                    title={props.page_settings.title}
                    subtitle={props.page_settings.subtitle}
                    icon={IconBuilding}
                />
                <Button variant="orange" size="xl" className="w-full lg:w-auto" asChild>
                    <Link href={route('students.study.plans.index')}>
                        <IconArrowBack className="size-4" />
                        Kembali
                    </Link>
                </Button>
            </div>
            <form onSubmit={onHandleSubmit}>
                <Table className="w-full">
                    <TableHeader>
                        <TableRow>
                            <TableHead>#</TableHead>
                            <TableHead>Mata Kuliah</TableHead>
                            <TableHead>Kelas</TableHead>
                            <TableHead>Hari</TableHead>
                            <TableHead>Jam</TableHead>
                            <TableHead>Kouta</TableHead>
                        </TableRow>
                    </TableHeader>
                    <TableBody>
                        {props.schedules.map((schedule, index) => (
                            <TableRow
                                key={index}
                                className={cn(schedule.taken_quota == schedule.quota && 'bg-red-500')}
                            >
                                <TableCell>
                                    <Checkbox
                                        id={`schedule_id_${schedule.id}`}
                                        name="schedule_id"
                                        checked={data.schedule_id.includes(schedule.id)}
                                        disabled={schedule.taken_quota == schedule.quota}
                                        onCheckedChange={(checked) => {
                                            if (checked) {
                                                setData('schedule_id', [...data.schedule_id, schedule.id]);
                                            } else {
                                                setData(
                                                    'schedule_id',
                                                    data.schedule_id.filter((id) => id !== schedule.id),
                                                );
                                            }
                                        }}
                                    />
                                </TableCell>
                                <TableCell>{schedule.course.name}</TableCell>
                                <TableCell>{schedule.classroom.name}</TableCell>
                                <TableCell>{schedule.day_of_week}</TableCell>
                                <TableCell>{schedule.start_time}</TableCell>
                                <TableCell
                                    className={cn(
                                        schedule.taken_quota == schedule.quota ? 'text-red-500' : 'text-green-500',
                                    )}
                                >
                                    {schedule.taken_quota} / {schedule.quota}
                                </TableCell>
                            </TableRow>
                        ))}
                    </TableBody>
                </Table>
                <div className="mt-8 flex flex-col gap-2 lg:flex-row lg:justify-end">
                    <Button type="button" variant="ghost" size="xl" onClick={onHandleReset}>
                        Reset
                    </Button>
                    <Button type="submit" variant="blue" size="xl" disabled={processing}>
                        <IconCheck className="mr-2 size-4" />
                        Save
                    </Button>
                </div>
            </form>
        </div>
    );
}

Create.layout = (page) => <StudentLayout children={page} title={page.props.page_settings.title} />;
