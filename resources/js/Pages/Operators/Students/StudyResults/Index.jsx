import AlertAction from '@/Components/AlertAction';
import EmptyState from '@/Components/EmptyState';
import Grades from '@/Components/Grades';
import HeaderTitle from '@/Components/HeaderTitle';
import PaginationTable from '@/Components/PaginationTable';
import ShowFilter from '@/Components/ShowFilter';
import { Avatar, AvatarFallback, AvatarImage } from '@/Components/ui/avatar';
import { Button } from '@/Components/ui/button';
import { Card, CardContent, CardFooter, CardHeader } from '@/Components/ui/card';
import { Input } from '@/Components/ui/input';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/Components/ui/select';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/Components/ui/table';
import UseFilter from '@/hooks/UseFilter';
import AppLayout from '@/Layouts/AppLayout';
import { deleteAction, formatDateIndo } from '@/lib/utils';
import { Link } from '@inertiajs/react';
import {
    IconArrowLeft,
    IconArrowsDownUp,
    IconBuilding,
    IconMoneybag,
    IconPencil,
    IconPlus,
    IconRefresh,
    IconSchool,
    IconTrash,
    IconUsers,
} from '@tabler/icons-react';
import { useEffect, useState } from 'react';
import { toast } from 'sonner';

export default function Index(props) {
    const { data: studyResults, meta, links } = props.studyResults;
    const [params, setParams] = useState({
        search: props.state?.search,
        page: props.state?.page,
        load: props.state?.load ?? props.page_settings.load,
    });


    return (
        <div className="flex w-full flex-col pb-32">
            <div className="mb-8 flex flex-col items-start justify-between gap-y-4 lg:flex-row lg:items-center">
                <HeaderTitle
                    title={props.page_settings.title}
                    subtitle={props.page_settings.subtitle}
                    icon={IconSchool}
                />
                <Button variant="orange" size="xl" className="w-full lg:w-auto" asChild>
                    <Link href={route('operators.students.index')}>
                        <IconArrowLeft className="size-4" />
                        Kembali
                    </Link>
                </Button>
            </div>
            <Card>
                <CardHeader className="mb-4 p-0">
                    {/* Show Filter */}
                    <ShowFilter params={params} />
                    <CardContent className="p-0 [&-td]:whitespace-nowrap [&-td]:px-6 [&-th]:px-6">
                        {studyResults.length == 0 ? (
                            <EmptyState
                                icon={IconSchool}
                                title="Tidak ada kartu hasil studi"
                                subtitle="Mulailah dengan membuat kartu hasil studi baru"
                            />
                        ) : (
                            <Table className="w-full">
                                <TableHeader>
                                    <TableRow>
                                        <TableCell>#</TableCell>
                                        <TableCell>Nomor Induk Mahasiswa</TableCell>
                                        <TableCell>Tahun Ajaran</TableCell>
                                        <TableCell>Semester</TableCell>
                                        <TableCell>Dibuat Pada</TableCell>
                                        <TableCell>Aksi</TableCell>
                                    </TableRow>
                                </TableHeader>
                                <TableBody>
                                    {studyResults.map((studyResult, index) => (
                                        <TableRow key={index}>
                                            <TableCell>{index + 1 + (meta.current_page - 1) * meta.per_page}</TableCell>
                                            <TableCell>{studyResult.student.student_number}</TableCell>
                                            <TableCell>{studyResult.academicYear.name}</TableCell>
                                            <TableCell>{studyResult.semester}</TableCell>
                                            <TableCell>{formatDateIndo(studyResult.created_at)}</TableCell>
                                            <TableCell>
                                                <div className="flex items-center gap-x-1">
                                                    <Grades
                                                        studyResult={studyResult}
                                                        grades={studyResult.grades}
                                                        name={studyResult.student.name}
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
                            {meta.total ?? 0} kartu hasil studi
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
