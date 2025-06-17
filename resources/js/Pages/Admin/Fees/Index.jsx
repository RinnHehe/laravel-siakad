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
import { formatDateIndo } from '@/lib/utils';
import { IconArrowsDownUp, IconMoneybag, IconRefresh } from '@tabler/icons-react';
import { useEffect, useState } from 'react';
import { toast } from 'sonner';

export default function Index(props) {
    const { data: fees, meta, links } = props.fees;
    const [params, setParams] = useState({
        search: props.state?.search,
        page: props.state?.page,
        load: props.state?.load,
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
        route: route('admin.fees.index'),
        values: params,
        only: ['fees'],
    });

    return (
        <div className="flex w-full flex-col pb-32">
            <div className="mb-8 flex flex-col items-start justify-between gap-y-4 lg:flex-row lg:items-center">
                <HeaderTitle
                    title={props.page_settings.title}
                    subtitle={props.page_settings.subtitle}
                    icon={IconMoneybag}
                />
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
                        {fees.length == 0 ? (
                            <EmptyState
                                icon={IconMoneybag}
                                title="Tidak ada uang kuliah tunggal"
                                subtitle="Mulailah dengan membuat uang kuliah tunggal baru"
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
                                        <TableHead> Jurusan </TableHead>
                                        <TableHead> Program Studi </TableHead>
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
                                        <TableHead> Nomor Pokok Mahasiswa </TableHead>
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
                                                onClick={() => onSortable('status')}
                                            >
                                                Status
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
                                    </TableRow>
                                </TableHeader>
                                <TableBody>
                                    {fees.map((fee, index) => (
                                        <TableRow key={index}>
                                            <TableCell>{index + 1 + (meta.current_page - 1) * meta.per_page}</TableCell>
                                            <TableCell>{fee.student.faculty}</TableCell>
                                            <TableCell>{fee.student.department}</TableCell>
                                            <TableCell>{fee.student.student_number}</TableCell>
                                            <TableCell>{fee.semester}</TableCell>
                                            <TableCell>{fee.status}</TableCell>
                                            <TableCell>{formatDateIndo(fee.created_at)}</TableCell>
                                        </TableRow>
                                    ))}
                                </TableBody>
                            </Table>
                        )}
                    </CardContent>
                    <CardFooter className="flex w-full flex-col items-center justify-between gap-y-2 border-t py-3 lg:flex-row">
                        <p className="text-sm text-muted-foreground">
                            Menampilkan <span className="font-medium text-blue-600">{meta.from ?? 0}</span> dari{' '}
                            {meta.total ?? 0} uang kuliah tunggal
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

Index.layout = (page) => <AppLayout children={page} title={page.props.page_settings.title} />;
