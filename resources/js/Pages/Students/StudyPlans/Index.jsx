import EmptyState from '@/Components/EmptyState';
import HeaderTitle from '@/Components/HeaderTitle';
import PaginationTable from '@/Components/PaginationTable';
import ShowFilter from '@/Components/ShowFilter';
import { Button } from '@/Components/ui/button';
import { Input } from '@/Components/ui/input';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/Components/ui/select';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/Components/ui/table';
import UseFilter from '@/hooks/UseFilter';
import StudentLayout from '@/Layouts/StudentLayout';
import { formatDateIndo, STUDYPLANSTATUSVARIANT } from '@/lib/utils';
import { Link } from '@inertiajs/react';
import { IconArrowsDownUp, IconBuilding, IconEye, IconPlus, IconRefresh } from '@tabler/icons-react';
import { useEffect, useState } from 'react';
import { toast } from 'sonner';
import { Badge } from '@/Components/ui/badge';
export default function Index(props) {
    const { data: studyPlans, meta, links } = props.studyPlans;
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
        route: route('students.study.plans.index'),
        values: params,
        only: ['study-plans'],
    });

    return (
        <div className="flex w-full flex-col pb-32">
            <div className="mb-8 flex flex-col items-start justify-between gap-y-4 lg:flex-row lg:items-center">
                <HeaderTitle
                    title={props.page_settings.title}
                    subtitle={props.page_settings.subtitle}
                    icon={IconBuilding}
                />
                <Button variant="orange" size="xl" className="w-full lg:w-auto" asChild>
                    <Link href={route('students.study.plans.create')}>
                        <IconPlus className="size-4" />
                        Tambah
                    </Link>
                </Button>
            </div>
            <div className="flex flex-col gap-y-8">
                {/* Filter */}
                <div className="flex w-full flex-col gap-4 lg:flex-row lg:items-center">
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
                {studyPlans.length == 0 ? (
                    <EmptyState
                        icon={IconBuilding}
                        title="Tidak ada kartu rencana studi"
                        subtitle="Mulailah dengan membuat kartu rencana studi"
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
                                        onClick={() => onSortable('academic_year_id')}
                                    >
                                        Tahun Ajaran
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
                                <TableHead>Aksi</TableHead>
                            </TableRow>
                        </TableHeader>
                        <TableBody>
                            {studyPlans.map((studyPlan, index) => (
                                <TableRow key={index}>
                                    <TableCell>{index + 1 + (meta.current_page - 1) * meta.per_page}</TableCell>
                                    <TableCell>{studyPlan.academicYear.name}</TableCell>
                                    <TableCell><Badge variant={STUDYPLANSTATUSVARIANT[studyPlan.status]}>{studyPlan.status}</Badge></TableCell>
                                    <TableCell>{formatDateIndo(studyPlan.created_at)}</TableCell>
                                    <TableCell>
                                        <div className="flex items-center gap-x-1">
                                            <Button variant="blue" size="sm" asChild>
                                                <Link href={route('students.study.plans.show', [studyPlan])}>
                                                    <IconEye className="size-4" />
                                                </Link>
                                            </Button>
                                        </div>
                                    </TableCell>
                                </TableRow>
                            ))}
                        </TableBody>
                    </Table>
                )}
                <div className="flex w-full flex-col items-center justify-between py-3 lg:flex-row">
                    <p className="text-sm text-muted-foreground">
                        Menampilkan <span className="font-medium text-blue-600">{meta.from ?? 0}</span> dari{' '}
                        {meta.total ?? 0} kartu rencana studi
                    </p>
                    <div className="overflow-x-auto">
                        {meta.has_pages && <PaginationTable meta={meta} links={links} />}
                    </div>
                </div>
            </div>
        </div>
    );
}

Index.layout = (page) => <StudentLayout children={page} title={page.props.page_settings.title} />;
