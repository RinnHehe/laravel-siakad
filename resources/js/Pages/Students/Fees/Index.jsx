import EmptyState from '@/Components/EmptyState';
import HeaderTitle from '@/Components/HeaderTitle';
import PaginationTable from '@/Components/PaginationTable';
import ShowFilter from '@/Components/ShowFilter';
import { Alert, AlertDescription, AlertTitle } from '@/Components/ui/alert';
import { Badge } from '@/Components/ui/badge';
import { Button } from '@/Components/ui/button';
import { Card, CardContent } from '@/Components/ui/card';
import { Input } from '@/Components/ui/input';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/Components/ui/select';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/Components/ui/table';
import UseFilter from '@/hooks/UseFilter';
import StudentLayout from '@/Layouts/StudentLayout';
import { FEESTATUSVARIANT, formatDateIndo, formatToRupiah, STUDYPLANSTATUSVARIANT } from '@/lib/utils';
import { Link, usePage } from '@inertiajs/react';
import { IconArrowsDownUp, IconBuilding, IconEye, IconMoneybag, IconPlus, IconRefresh } from '@tabler/icons-react';
import { useEffect, useState } from 'react';
import { toast } from 'sonner';
export default function Index(props) {
    const auth = usePage().props.auth.user;
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
        route: route('students.fees.index'),
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
            <div className="flex flex-col gap-y-8">
                {/* Pembayaran */}
                {!props.checkFee && (
                    <div>
                        <Alert variant='orange'>
                            <AlertTitle> Periode pembayaran UKT tahun ajaran {props.academic_year.name} </AlertTitle>
                            <AlertDescription> Silahkan untuk melakukan pembayaran UKT terlebih dahulu agar dapat mengajukan kartu rencana studi </AlertDescription>
                        </Alert>
                    </div>
                )}

                {(props.fee && props.fee.status != 'Sukses') || !props.fee && (
                    <Card>
                        <CardContent className='p-6 space-y-20'>
                            <div>
                                <Table className='w-full'>
                                    <TableHeader>
                                        <TableRow>
                                            <TableHead>Nama</TableHead>
                                            <TableHead>NIM</TableHead>
                                            <TableHead>Semester</TableHead>
                                            <TableHead>Kelas</TableHead>
                                            <TableHead>Program Studi</TableHead>
                                            <TableHead>Jurusan</TableHead>
                                            <TableHead>Total Tagihan</TableHead>
                                            <TableHead>Aksi</TableHead>
                                        </TableRow>
                                    </TableHeader>
                                    <TableBody>
                                        <TableRow>
                                            <TableCell>{auth.name}</TableCell>
                                            <TableCell>{auth.student.student_number}</TableCell>
                                            <TableCell>{auth.student.semester}</TableCell>
                                            <TableCell>{auth.student.classroom.name}</TableCell>
                                            <TableCell>{auth.student.department.name}</TableCell>
                                            <TableCell>{auth.student.faculty.name}</TableCell>
                                            <TableCell>{formatToRupiah(auth.student.feeGroup.amount)}</TableCell>
                                            <TableCell>
                                                <Button variant='orange'>
                                                    Bayar
                                                </Button>
                                            </TableCell>
                                        </TableRow>
                                    </TableBody>
                                </Table>
                            </div>

                        </CardContent>
                    </Card>
                )}
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
                {fees.length == 0 ? (
                    <EmptyState
                        icon={IconMoneybag}
                        title="Tidak ada pembayaran"
                        subtitle="Mulailah dengan membuat pembayaran"
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
                                        onClick={() => onSortable('fee_code')}
                                    >
                                        Kode Pembayaran
                                        <span className="ml-2 flex-none rounded text-muted-foreground"></span>
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
                                    <TableCell>{fee.fee_code}</TableCell>
                                    <TableCell>{fee.feeGroup.group}</TableCell>
                                    <TableCell>{fee.academicYear.name}</TableCell>
                                    <TableCell>{fee.semester}</TableCell>
                                    <TableCell>
                                        <Badge variant={FEESTATUSVARIANT[fee.status]}> {fee.status} </Badge>
                                    </TableCell>
                                    <TableCell>{formatDateIndo(fee.created_at)}</TableCell>
                                </TableRow>
                            ))}
                        </TableBody>
                    </Table>
                )}
                <div className="flex w-full flex-col items-center justify-between py-3 lg:flex-row">
                    <p className="text-sm text-muted-foreground">
                        Menampilkan <span className="font-medium text-blue-600">{meta.from ?? 0}</span> dari{' '}
                        {meta.total ?? 0} pembayaran
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
