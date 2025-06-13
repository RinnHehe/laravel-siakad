import AlertAction from '@/Components/AlertAction';
import EmptyState from '@/Components/EmptyState';
import HeaderTitle from '@/Components/HeaderTitle';
import PaginationTable from '@/Components/PaginationTable';
import ShowFilter from '@/Components/ShowFilter';
import { Button } from '@/Components/ui/button';
import { Card, CardContent, CardFooter, CardHeader } from '@/Components/ui/card';
import { Input } from '@/Components/ui/input';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/Components/ui/select';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/Components/ui/table';
import UseFilter from '@/hooks/UseFilter';
import AppLayout from '@/Layouts/AppLayout';
import { deleteAction, formatDateIndo } from '@/lib/utils';
import { Link } from '@inertiajs/react';
import { IconArrowsDownUp, IconBooks, IconPencil, IconPlus, IconRefresh, IconTrash } from '@tabler/icons-react';
import { useEffect, useState } from 'react';
import { toast } from 'sonner';

export default function Index(props) {
    const { data: courses, meta, links } = props.courses;
    const [params, setParams] = useState({
        search: props.state?.search,
        page: props.state?.page,
        load: props.state?.load ?? props.page_settings.load,
    });

    useEffect(() => {
        // Check for flash message
        const flash = props.flash_message;
        if (flash) {
            if (flash.type === 'success') {
                toast.success(flash.message);
            } else if (flash.type === 'error') {
                toast.error(flash.message);
            }
        }
    }, []);

    const onSortable = (field) => {
        setParams({
            ...params,
            field: field,
            direction: params.direction === 'asc' ? 'desc' : 'asc',
        });
    };

    UseFilter({
        route: route('admin.courses.index'),
        values: params,
        only: ['courses'],
    });

    return (
        <div className="flex w-full flex-col pb-32">
            <div className="mb-8 flex flex-col items-start justify-between gap-y-4 lg:flex-row lg:items-center">
                <HeaderTitle
                    title={props.page_settings.title}
                    subtitle={props.page_settings.subtitle}
                    icon={IconBooks}
                />
                <Button variant="orange" size="xl" className="w-full lg:w-auto" asChild>
                    <Link href={route('admin.courses.create')}>
                        <IconPlus className="size-4" />
                        Tambah
                    </Link>
                </Button>
            </div>
            <Card>
                <CardHeader className="mb-4 p-0">
                    {/* Filter */}
                    <div className="flex w-full flex-col gap-4 px-6 py-4 lg:flex-row lg:items-center">
                        <Input
                            className="w-full sm:w-1/4"
                            placeholder="Cari..."
                            value={params?.search}
                            onChange={(e) => setParams((prev) => ({ ...prev, search: e.target.value }))}
                        />
                        <Select value={params?.load} onValueChange={(e) => setParams({ ...params, load: e })}>
                            <SelectTrigger className="w-full sm:w-24">
                                <SelectValue placeholder="Load" />
                            </SelectTrigger>
                            <SelectContent>
                                {[10, 25, 50, 75, 100].map((number, index) => (
                                    <SelectItem key={index} value={number}>
                                        {number}
                                    </SelectItem>
                                ))}
                            </SelectContent>
                        </Select>
                        <Button variant="red" onClick={() => setParams(props.state)} size="xl">
                            <IconRefresh className="size-4" />
                            Bersihkan
                        </Button>
                    </div>
                    {/* Show Filter */}
                    <ShowFilter params={params} />
                    <CardContent className="p-0 [&-td]:whitespace-nowrap [&-td]:px-6 [&-th]:px-6">
                        {courses.length == 0 ? (
                            <EmptyState
                                icon={IconBooks}
                                title="Tidak ada mata kuliah"
                                subtitle="Mulailah dengan membuat mata kuliah baru"
                            />
                        ) : (
                            <Table className="w-full">
                                <TableHeader>
                                    <TableRow>
                                        <TableHead>
                                            <Button
                                                variant="ghost"
                                                className="group inline-flex"
                                                onClick={() => onSortable('id')}
                                            >
                                                #<span className="ml-2 flex-none rounded text-muted-foreground"></span>
                                                <IconArrowsDownUp className="size-4" />
                                            </Button>
                                        </TableHead>
                                        <TableHead>
                                            <Button
                                                variant="ghost"
                                                className="group inline-flex"
                                                onClick={() => onSortable('faculty_id')}
                                            >
                                                Jurusan
                                                <span className="ml-2 flex-none rounded text-muted-foreground"></span>
                                                <IconArrowsDownUp className="size-4" />
                                            </Button>
                                        </TableHead>
                                        <TableHead>
                                            <Button
                                                variant="ghost"
                                                className="group inline-flex"
                                                onClick={() => onSortable('department_id')}
                                            >
                                                Program Studi
                                                <span className="ml-2 flex-none rounded text-muted-foreground"></span>
                                                <IconArrowsDownUp className="size-4" />
                                            </Button>
                                        </TableHead>
                                        <TableHead>
                                            <Button
                                                variant="ghost"
                                                className="group inline-flex"
                                                onClick={() => onSortable('teacher_id')}
                                            >
                                                Dosen
                                                <span className="ml-2 flex-none rounded text-muted-foreground"></span>
                                                <IconArrowsDownUp className="size-4" />
                                            </Button>
                                        </TableHead>
                                        <TableHead>
                                            <Button
                                                variant="ghost"
                                                className="group inline-flex"
                                                onClick={() => onSortable('code')}
                                            >
                                                Kode Mata Kuliah
                                                <span className="ml-2 flex-none rounded text-muted-foreground"></span>
                                                <IconArrowsDownUp className="size-4" />
                                            </Button>
                                        </TableHead>
                                        <TableHead>
                                            <Button
                                                variant="ghost"
                                                className="group inline-flex"
                                                onClick={() => onSortable('name')}
                                            >
                                                Nama
                                                <span className="ml-2 flex-none rounded text-muted-foreground"></span>
                                                <IconArrowsDownUp className="size-4" />
                                            </Button>
                                        </TableHead>
                                        <TableHead>
                                            <Button
                                                variant="ghost"
                                                className="group inline-flex"
                                                onClick={() => onSortable('credit')}
                                            >
                                                SKS
                                                <span className="ml-2 flex-none rounded text-muted-foreground"></span>
                                                <IconArrowsDownUp className="size-4" />
                                            </Button>
                                        </TableHead>
                                        <TableHead>
                                            <Button
                                                variant="ghost"
                                                className="group inline-flex"
                                                onClick={() => onSortable('semester')}
                                            >
                                                Semester
                                                <span className="ml-2 flex-none rounded text-muted-foreground"></span>
                                                <IconArrowsDownUp className="size-4" />
                                            </Button>
                                        </TableHead>
                                        <TableHead>
                                            <Button
                                                variant="ghost"
                                                className="group inline-flex"
                                                onClick={() => onSortable('created_at')}
                                            >
                                                Dibuat Pada
                                                <span className="ml-2 flex-none rounded text-muted-foreground"></span>
                                                <IconArrowsDownUp className="size-4" />
                                            </Button>
                                        </TableHead>
                                        <TableHead>Aksi</TableHead>
                                    </TableRow>
                                </TableHeader>
                                <TableBody>
                                    {courses.map((course, index) => (
                                        <TableRow key={index}>
                                            <TableCell>{index + 1 + (meta.current_page - 1) * meta.per_page}</TableCell>
                                            <TableCell>{course.faculty?.name}</TableCell>
                                            <TableCell>{course.department?.name}</TableCell>
                                            <TableCell>{course.teacher?.name}</TableCell>
                                            <TableCell>{course.code}</TableCell>
                                            <TableCell>{course.name}</TableCell>
                                            <TableCell>{course.credit}</TableCell>
                                            <TableCell>{course.semester}</TableCell>
                                            <TableCell>{formatDateIndo(course.created_at)}</TableCell>
                                            <TableCell>
                                                <div className="flex items-center gap-x-1">
                                                    <Button variant="blue" size="sm" asChild>
                                                        <Link href={route('admin.courses.edit', { course })}>
                                                            <IconPencil className="size-4" />
                                                        </Link>
                                                    </Button>
                                                    <AlertAction
                                                        trigger={
                                                            <Button variant="red" size="sm">
                                                                <IconTrash className="size-4" />
                                                            </Button>
                                                        }
                                                        action={() =>
                                                            deleteAction(route('admin.courses.destroy', { course }))
                                                        }
                                                    />
                                                </div>
                                            </TableCell>
                                        </TableRow>
                                    ))}
                                </TableBody>
                            </Table>
                        )}
                    </CardContent>
                    <CardFooter className="flex w-full flex-col items-center justify-between gap-y-2 border-t py-3 lg:flex-row">
                        <p className="text-sm text-muted-foreground">
                            Menampilkan <span className="font-medium text-blue-600">{meta.from ?? 0}</span> dari{' '}
                            {meta.total ?? 0} mata kuliah
                        </p>
                        <div className="overflow-x-auto">
                            {meta.has_pages && <PaginationTable meta={meta} links={links} />}
                        </div>
                    </CardFooter>
                </CardHeader>
            </Card>
        </div>
    );
}

Index.layout = (page) => <AppLayout title={page.props.page_settings.title} children={page} />;
