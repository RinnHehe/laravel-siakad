import HeaderTitle from '@/Components/HeaderTitle';
import InputError from '@/Components/InputError';
import { Button } from '@/Components/ui/button';
import { Card, CardContent } from '@/Components/ui/card';
import { Input } from '@/Components/ui/input';
import { Label } from '@/Components/ui/label';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/Components/ui/select';
import AppLayout from '@/Layouts/AppLayout';
import { flashMessage } from '@/lib/utils';
import { Link, useForm } from '@inertiajs/react';
import { IconArrowLeft, IconCalendar, IconCheck, IconSchool, IconUser, IconUsers, IconUsersGroup } from '@tabler/icons-react';
import { toast } from 'sonner';
import { useRef } from 'react';

export default function Create(props) {
    const { data, setData, post, processing, errors, reset } = useForm({
        course_id: null ?? '',
        classroom_id: null ?? '',
        start_time: '',
        end_time: '',
        day_of_week: null ?? '',
        quota: 0,
        _method: props.page_settings.method,
    });

    const onHandleChange = (e) => setData(e.target.name, e.target.value);
    const onHandleSubmit = (e) => {
        e.preventDefault();
        post(props.page_settings.action, {
            preserveScroll: true,
            onSuccess: (success) => {
                const flash = flashMessage(success);
                if (flash) toast[flash.type](flash.message);
            },
        });
    };

    const onHandleReset = () => {
        reset();
    };

    return (
        <div className="flex w-full flex-col pb-32">
            <div className="mb-8 flex flex-col items-start justify-between gap-y-4 lg:flex-row lg:items-center">
                <HeaderTitle
                    title={props.page_settings.title}
                    subtitle={props.page_settings.subtitle}
                    icon={IconCalendar}
                />
                <Button variant="orange" size="xl" className="w-full lg:w-auto" asChild>
                        <Link href={route('operators.schedules.index')}>
                        <IconArrowLeft className="size-4" />
                        Kembali
                    </Link>
                </Button>
            </div>

            <Card>
                <CardContent>
                    <form onSubmit={onHandleSubmit}>
                        <div className="grid grid-cols-1 gap-4 lg:grid-cols-4">
                            <div className="col-span-full">
                                <Label htmlFor="course_id">Mata Kuliah</Label>
                                <Select
                                    defaultValue={data.course_id}
                                    onValueChange={(value) => setData('course_id', value)}
                                    name="course_id"
                                    id="course_id"
                                >
                                    <SelectTrigger>
                                        <SelectValue placeholder="Pilih Mata Kuliah">
                                            {props.courses.find((course) => course.value == data.course_id)
                                                ?.label ?? 'Pilih Mata Kuliah'}
                                        </SelectValue>
                                    </SelectTrigger>
                                    <SelectContent>
                                        {props.courses.map((course, index) => (
                                            <SelectItem key={index} value={course.value}>
                                                {course.label}
                                            </SelectItem>
                                        ))}
                                    </SelectContent>
                                </Select>
                                {errors.course_id && <InputError message={errors.course_id} />}
                            </div>
                            <div className="col-span-full">
                                <Label htmlFor="classroom_id">Kelas</Label>
                                <Select
                                    defaultValue={data.classroom_id}
                                    onValueChange={(value) => setData('classroom_id', value)}
                                    name="classroom_id"
                                    id="classroom_id"
                                >
                                    <SelectTrigger>
                                        <SelectValue placeholder="Pilih Kelas">
                                            {props.classrooms.find((classroom) => classroom.value == data.classroom_id)
                                                ?.label ?? 'Pilih Kelas'}
                                        </SelectValue>
                                    </SelectTrigger>
                                    <SelectContent>
                                        {props.classrooms.map((classroom, index) => (
                                            <SelectItem key={index} value={classroom.value}>
                                                {classroom.label}
                                            </SelectItem>
                                        ))}
                                    </SelectContent>
                                </Select>
                                {errors.classroom_id && <InputError message={errors.classroom_id} />}
                            </div>
                            <div className="col-span-2">
                                <Label htmlFor="start_time">Waktu Mulai</Label>
                                <Input
                                    type="time"
                                    name="start_time"
                                    id="start_time"
                                    value={data.start_time}
                                    onChange={onHandleChange}
                                    placeholder="Masukkan waktu mulai"
                                />
                                {errors.start_time && <InputError message={errors.start_time} />}
                            </div>
                            <div className="col-span-2">
                                <Label htmlFor="end_time">Waktu Selesai</Label>
                                <Input
                                    type="time"
                                    name="end_time"
                                    id="end_time"
                                    value={data.end_time}
                                    onChange={onHandleChange}
                                    placeholder="Masukkan waktu selesai"
                                />
                                {errors.end_time && <InputError message={errors.end_time} />}
                            </div>
                            <div className="col-span-full">
                                <Label htmlFor="day_of_week">Hari</Label>
                                <Select
                                    defaultValue={data.day_of_week}
                                    onValueChange={(value) => setData('day_of_week', value)}
                                    name="day_of_week"
                                    id="day_of_week"
                                >
                                    <SelectTrigger>
                                        <SelectValue placeholder="Pilih Hari">
                                            {props.days.find((day) => day.value == data.day_of_week)
                                                ?.label ?? 'Pilih Hari'}
                                        </SelectValue>
                                    </SelectTrigger>
                                    <SelectContent>
                                        {props.days.map((day, index) => (
                                            <SelectItem key={index} value={day.value}>
                                                {day.label}
                                            </SelectItem>
                                        ))}
                                    </SelectContent>
                                </Select>
                                {errors.day_of_week && <InputError message={errors.day_of_week} />}
                            </div>
                            <div className="col-span-full">
                                <Label htmlFor="quota">Kuota</Label>
                                <Input
                                    type="number"
                                    name="quota"
                                    id="quota"
                                    value={data.quota}
                                    onChange={onHandleChange}
                                    placeholder="Masukkan kuota"
                                />
                                {errors.quota && <InputError message={errors.quota} />}
                            </div>
                        </div>
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
                </CardContent>
            </Card>
        </div>
    );
}

Create.layout = (page) => <AppLayout children={page} title={page.props.page_settings.title} />;
