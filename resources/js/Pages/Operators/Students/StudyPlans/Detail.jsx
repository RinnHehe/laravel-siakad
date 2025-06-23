import { Button } from "@/Components/ui/button";
import { Sheet, SheetContent, SheetDescription, SheetHeader, SheetTitle, SheetTrigger } from "@/Components/ui/sheet";
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from "@/Components/ui/table";
import { IconEye } from "@tabler/icons-react";

export default function Detail({ schedules, name }) {
    return (
        <Sheet>
            <SheetTrigger asChild>
                <Button
                    variant="purple"
                    size="sm"
                >
                    <IconEye className="size-4 text-white" />
                </Button>
            </SheetTrigger>
            <SheetContent side='top'>
                <SheetHeader>
                    <SheetTitle>Detail Kartu Rencana Studi {name}</SheetTitle>
                    <SheetDescription>Detail kartu rencana studi mahasiswa yang diajukan</SheetDescription>
                </SheetHeader>
                <Table className='w-full'>
                    <TableHeader>
                        <TableRow>
                            <TableHead>#</TableHead>
                            <TableHead>Mata Kuliah</TableHead>
                            <TableHead>SKS</TableHead>
                            <TableHead>Kelas</TableHead>
                            <TableHead>Tahun Ajaran</TableHead>
                            <TableHead>Waktu</TableHead>
                        </TableRow>
                    </TableHeader>
                    <TableBody>
                        {schedules.map((schedule, index) => (
                            <TableRow key={index}>
                                <TableCell>{index + 1}</TableCell>
                                <TableCell>{schedule.course.name}</TableCell>
                                <TableCell>{schedule.course.credit}</TableCell>
                                <TableCell>{schedule.classroom.name}</TableCell>
                                <TableCell>{schedule.academicYear.name}</TableCell>
                                <TableCell>{schedule.day_of_week}, {schedule.start_time} : {schedule.end_time}</TableCell>
                            </TableRow>
                        ))}
                    </TableBody>
                </Table>
            </SheetContent>
        </Sheet>
    )
}