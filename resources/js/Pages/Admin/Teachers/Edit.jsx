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
import { IconArrowLeft, IconCheck, IconUsersGroup } from '@tabler/icons-react';
import { useRef } from 'react';
import { toast } from 'sonner';

export default function Edit(props) {
    const fileInputAvatar = useRef(null);
    const { data, setData, post, processing, errors, reset } = useForm({
        faculty_id: props.teacher.faculty_id ?? null,
        department_id: props.teacher.user.department_id ?? null,
        name: props.teacher.user.name ?? '',
        email: props.teacher.user.email ?? '',
        password: '',
        avatar: null,
        teacher_number: props.teacher.teacher_number ?? '',
        academic_title: props.teacher.academic_title ?? '',
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
        fileInputAvatar.current.value = null;
    };

    return (
        <div className="flex w-full flex-col pb-32">
            <div className="mb-8 flex flex-col items-start justify-between gap-y-4 lg:flex-row lg:items-center">
                <HeaderTitle
                    title={props.page_settings.title}
                    subtitle={props.page_settings.subtitle}
                    icon={IconUsersGroup}
                />
                <Button variant="orange" size="xl" className="w-full lg:w-auto" asChild>
                    <Link href={route('admin.teachers.index')}>
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
                                <Label htmlFor="name">Nama</Label>
                                <Input
                                    type="text"
                                    name="name"
                                    id="name"
                                    value={data.name}
                                    onChange={onHandleChange}
                                    placeholder="Masukkan nama dosen"
                                />
                                {errors.name && <InputError message={errors.name} />}
                            </div>
                            <div className="col-span-2">
                                <Label htmlFor="email">Email</Label>
                                <Input
                                    type="email"
                                    name="email"
                                    id="email"
                                    value={data.email}
                                    onChange={onHandleChange}
                                    placeholder="Masukkan alamat email dosen"
                                />
                                {errors.email && <InputError message={errors.email} />}
                            </div>
                            <div className="col-span-2">
                                <Label htmlFor="password">Password</Label>
                                <Input
                                    type="password"
                                    name="password"
                                    id="password"
                                    value={data.password}
                                    onChange={onHandleChange}
                                    placeholder="********"
                                />
                                {errors.password && <InputError message={errors.password} />}
                            </div>
                            <div className="col-span-full">
                                <Label htmlFor="faculty_id">Jurusan</Label>
                                <Select
                                    defaultValue={data.faculty_id}
                                    onValueChange={(value) => setData('faculty_id', value)}
                                >
                                    <SelectTrigger>
                                        <SelectValue placeholder="Pilih Jurusan">
                                            {props.faculties.find((faculty) => faculty.value == data.faculty_id)
                                                ?.label ?? 'Pilih Jurusan'}
                                        </SelectValue>
                                    </SelectTrigger>
                                    <SelectContent>
                                        {props.faculties.map((faculty, index) => (
                                            <SelectItem key={index} value={faculty.value}>
                                                {faculty.label}
                                            </SelectItem>
                                        ))}
                                    </SelectContent>
                                </Select>
                                {errors.faculty_id && <InputError message={errors.faculty_id} />}
                            </div>
                            <div className="col-span-full">
                                <Label htmlFor="department_id">Program Studi</Label>
                                <Select
                                    defaultValue={data.department_id}
                                    onValueChange={(value) => setData('department_id', value)}
                                >
                                    <SelectTrigger>
                                        <SelectValue placeholder="Pilih Program Studi">
                                            {props.departments.find(
                                                (department) => department.value == data.department_id,
                                            )?.label ?? 'Pilih Program Studi'}
                                        </SelectValue>
                                    </SelectTrigger>
                                    <SelectContent>
                                        {props.departments.map((department, index) => (
                                            <SelectItem key={index} value={department.value}>
                                                {department.label}
                                            </SelectItem>
                                        ))}
                                    </SelectContent>
                                </Select>
                                {errors.department_id && <InputError message={errors.department_id} />}
                            </div>
                            <div className="col-span-2">
                                <Label htmlFor="teacher_number">Nomor Induk Dosen</Label>
                                <Input
                                    type="text"
                                    name="teacher_number"
                                    id="teacher_number"
                                    value={data.teacher_number}
                                    onChange={onHandleChange}
                                    placeholder="Masukkan nomor induk dosen"
                                />
                                {errors.teacher_number && <InputError message={errors.teacher_number} />}
                            </div>
                            <div className="col-span-2">
                                <Label htmlFor="academic_title">Jabatan Akademik</Label>
                                <Input
                                    type="text"
                                    name="academic_title"
                                    id="academic_title"
                                    value={data.academic_title}
                                    onChange={onHandleChange}
                                    placeholder="Masukkan jabatan akademik"
                                />
                                {errors.academic_title && <InputError message={errors.academic_title} />}
                            </div>
                            <div className="col-span-2">
                                <Label htmlFor="avatar">Avatar</Label>
                                <Input
                                    type="file"
                                    name="avatar"
                                    id="avatar"
                                    onChange={(e) => setData('avatar', e.target.files[0])}
                                    ref={fileInputAvatar}
                                    accept="image/*"
                                />
                                {errors.avatar && <InputError message={errors.avatar} />}
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

Edit.layout = (page) => <AppLayout children={page} title={page.props.page_settings.title} />;
