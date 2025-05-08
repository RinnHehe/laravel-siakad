import { Button } from '@Components/ui/button';
import { IconEye } from '@tabler/icons-react';
import { Table, TableBody, TableHead, TableHeader, TableRow } from "@Components/ui/table";
import { Sheet, SheetContent, SheetTrigger, SheetHeader, SheetTitle, SheetDescription } from '@Components/ui/sheet';

export default function Grades({ studyResult, grades, name = null }) {
  return (
    <Sheet>
      <SheetTrigger asChild>
        <Button variant="purple" size="sm">
          <IconEye className="text-white size-4" />
        </Button>
      </SheetTrigger>
      <SheetContent side='top'>
        <SheetHeader>
          <SheetTitle>Detail Kartu Hasil Studi Mahasiswa {name}</SheetTitle>
          <SheetDescription>Detail kartu hasil studi mahasiswa</SheetDescription>
        </SheetHeader>
        <Table className="w-full border">
          <TableHeader>
            <TableRow>
              <TableHead className='border'>NO</TableHead>
              <TableHead className='border'>KODE</TableHead>
              <TableHead className='border'>MATAKULIAH</TableHead>
              <TableHead className='border'>SKS</TableHead>
              <TableHead className='border'>HURUF MUTU</TableHead>
              <TableHead className='border'>BOBOT</TableHead>
              <TableHead className='border'>NILAI</TableHead>
            </TableRow>
          </TableHeader>
          <TableBody>
            {grades.map((grade, index) => (
              <TableRow key={index}>
                <TableCell className="border">{index + 1}</TableCell>
                <TableCell className="border">{grade.course.code}</TableCell>
                <TableCell className="border">{grade.course.name}</TableCell>
                <TableCell className="border">{grade.course.credit}</TableCell>
                <TableCell className="border">{grade.letter}</TableCell>
                <TableCell className="border">{grade.weight_of_value}</TableCell>
                <TableCell className="border">{grade.grade}</TableCell>
              </TableRow>
            ))}
          </TableBody>
          <TableFooter className="font-bold">
            <TableRow>
              <TableCell colSpan="3">IP Semester</TableCell>
              <TableCell className="border">{studyResult.gpa}</TableCell>
              <TableCell className="border"></TableCell>
              <TableCell className="border"></TableCell>
            </TableRow>
          </TableFooter>
        </Table>
      </SheetContent>
    </Sheet>
  );
}
