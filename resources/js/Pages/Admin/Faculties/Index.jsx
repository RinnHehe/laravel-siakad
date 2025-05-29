import AlertAction from "@/Components/AlertAction";
import EmptyState from "@/Components/EmptyState";
import HeaderTitle from "@/Components/HeaderTitle";
import PaginationTable from "@/Components/PaginationTable";
import ShowFilter from "@/Components/ShowFilter";
import { Avatar, AvatarFallback, AvatarImage } from "@/Components/ui/avatar";
import { Button } from "@/Components/ui/button";
import { Card, CardContent, CardFooter, CardHeader } from "@/Components/ui/card";
import { Input } from "@/Components/ui/input";
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from "@/Components/ui/select";
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from "@/Components/ui/table";
import UseFilter from "@/hooks/UseFilter";
import AppLayout from "@/Layouts/AppLayout";
import { formatDateIndo } from "@/lib/utils";
import { Link } from "@inertiajs/react";
import { IconArrowsDownUp, IconBuildingSkyscraper, IconPencil, IconPlus, IconRefresh, IconSearch, IconTrash } from "@tabler/icons-react";
import { useState } from "react";

export default function Index(props) {
    const { data: faculties, meta, links } = props.faculties;
    const [params, setParams] = useState({
        search: props.state?.search,
        page: props.state?.page,
        load: props.state?.load,
    });

    const onSortable = (field) => {
        setParams({
            ...params,
            field: field,
            direction: params.direction === 'asc' ? 'desc' : 'asc',
        });
    };

    UseFilter({
        route: route('admin.faculties.index'),
        values: params,
        only: ['faculties'],
    });

    return (
        <div className="flex flex-col w-full pb-32">
            <div className="mb-8 flex flex-col items-start justify-between gap-y-4 lg:flex-row lg:items-center">
                <HeaderTitle
                    title={props.page_settings.title}
                    subtitle={props.page_settings.subtitle}
                    icon={IconBuildingSkyscraper}
                />
                <Button
                    variant="orange"
                    size="xl"
                    className="w-full lg:w-auto"
                    asChild
                >
                    <Link href={route('admin.faculties.create')}>
                        <IconPlus className="size-4"/>
                        Tambah
                    </Link>
                </Button>
            </div>
            <Card>
                <CardHeader className="p-0 mb-4">
                    {/* Filter */}
                    <div className="flex flex-col w-full gap-4 px-6 py-4 lg:flex-row lg:items-center">
                        <Input
                            className="w-full sm:w-1/4"
                            placeholder="Cari jurusan..."
                            value={params?.search}
                            onChange={(e) => setParams((prev) => ({ ...prev, search: e.target.value }))}
                        />
                            <Select value={params?.load} onValueChange={(e) => setParams({ ...params, load: e})}>
                                <SelectTrigger className="w-full sm:w-24">
                                    <SelectValue placeholder="Load" />
                                </SelectTrigger>
                                <SelectContent>
                                    {[10,25,50,75,100].map((number, index) => (
                                        <SelectItem key={index} value={number}>
                                            {number}
                                        </SelectItem>
                                    ))}
                                </SelectContent>
                            </Select>
                            <Button variant='red' onClick={() => setParams(props.state)} size='xl'>
                                <IconRefresh className="size-4"/>
                                Bersihkan
                            </Button>
                    </div>
                    {/* Show Filter */}
                    <ShowFilter params={params} />
                    <CardContent className="p-0 [&-td]:whitespace-nowrap [&-td]:px-6 [&-th]:px-6">
                        {faculties.length == 0 ? (
                            <EmptyState
                                icon={IconBuildingSkyscraper}
                                title="Tidak ada jurusan"
                                subtitle="Mulailah dengan membuat jurusan baru"
                            />
                        ) : (
                            <Table className="w-full">
                                <TableHeader>
                                    <TableRow>
                                        <TableHead>
                                            <Button variant='ghost' className="inline-flex group" onClick={() => onSortable('id')}>
                                                #
                                                <span className="flex-none ml-2 rounded text-muted-foreground"></span>
                                                <IconArrowsDownUp className="size-4"/>
                                            </Button>
                                        </TableHead>
                                        <TableHead>
                                        <Button variant='ghost' className="inline-flex group" onClick={() => onSortable('name')}>
                                                Nama
                                                <span className="flex-none ml-2 rounded text-muted-foreground"></span>
                                                <IconArrowsDownUp className="size-4"/>
                                            </Button>
                                        </TableHead>
                                        <TableHead>
                                        <Button variant='ghost' className="inline-flex group" onClick={() => onSortable('code')}>
                                                Kode
                                                <span className="flex-none ml-2 rounded text-muted-foreground"></span>
                                                <IconArrowsDownUp className="size-4"/>
                                            </Button>
                                        </TableHead>
                                        <TableHead>Logo</TableHead>
                                        <TableHead>
                                        <Button variant='ghost' className="inline-flex group" onClick={() => onSortable('created_at')}>
                                                Dibuat Pada
                                                <span className="flex-none ml-2 rounded text-muted-foreground"></span>
                                                <IconArrowsDownUp className="size-4"/>
                                            </Button>
                                        </TableHead>
                                        <TableHead>Aksi</TableHead>
                                    </TableRow>
                                </TableHeader>
                                <TableBody>
                                    {faculties.map((faculty, index) => (
                                        <TableRow key={index}>
                                            <TableCell>{index + 1 + (meta.current_page - 1) * meta.per_page}</TableCell>
                                            <TableCell>{faculty.name}</TableCell>
                                            <TableCell>{faculty.code}</TableCell>
                                            <TableCell>
                                                <Avatar>
                                                    <AvatarImage src={faculty.logo} />
                                                    <AvatarFallback>{faculty.name.substring(0, 1)}</AvatarFallback>
                                                </Avatar>
                                            </TableCell>
                                            <TableCell>{formatDateIndo(faculty.created_at)}</TableCell>
                                            <TableCell>
                                                <div className="flex items-center gap-x-1">
                                                    <Button variant="blue" size="sm" asChild>
                                                        <Link href={route('admin.faculties.edit', [faculty])}>
                                                            <IconPencil className="size-4"/>
                                                        </Link>
                                                    </Button>
                                                    <AlertAction
                                                        trigger={
                                                            <Button variant="red" size="sm">
                                                                <IconTrash className="size-4"/>
                                                            </Button>
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
                    <CardFooter className="flex flex-col items-center justify-between w-full py-3 border-t gap-y-2 lg:flex-row">
                        <p className="text-sm text-muted-foreground">
                            Menampilkan <span className="font-medium text-blue-600">{meta.from ?? 0}</span> dari {meta.total ?? 0} jurusan
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

Index.layout = (page) => <AppLayout title={page.props.page_settings.title} children={page} />