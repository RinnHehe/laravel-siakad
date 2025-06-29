import EmptyState from '@/Components/EmptyState';
import HeaderTitle from '@/Components/HeaderTitle';
import ShowFilter from '@/Components/ShowFilter';
import { Alert, AlertDescription } from '@/Components/ui/alert';
import { Avatar, AvatarFallback, AvatarImage } from '@/Components/ui/avatar';
import { Button } from '@/Components/ui/button';
import { Card, CardContent, CardHeader } from '@/Components/ui/card';
import { Checkbox } from '@/Components/ui/checkbox';
import { Input } from '@/Components/ui/input';
import { Table, TableBody, TableCell, TableFooter, TableHead, TableHeader, TableRow } from '@/Components/ui/table';
import UseFilter from '@/hooks/UseFilter';
import AppLayout from '@/Layouts/AppLayout';
import { flashMessage } from '@/lib/utils';
import { useForm } from '@inertiajs/react';
import { IconCheck, IconDoor, IconRefresh } from '@tabler/icons-react';
import { useState } from 'react';
import { toast } from 'sonner';

export default function Index(props) {
    const students = props.students;
    const [params, setParams] = useState(props.state);

    UseFilter({
        route: route('teachers.classrooms.index', [props.course, props.classroom]),
        value: params,
        only: ['students'],
    });

    const { data, setData, post, errors, processing, reset } = useForm({
        attendances: [],
        grades: [],
        _method: props.page_settings.method,
    });

    const onHandleSubmit = (e) => {
        e.preventDefault();
        post(props.page_settings.action, {
            preserveScroll: true,
            preserveState: true,
            onSuccess: (success) => {
                const flash = flashMessage(success);
                if (flash) toast[flash.type](flash.message);
                reset();
            },
        });
    };

    const isAttendanceChecked = (attendances, studentId, section) => {
        return attendances.some(
            (attendance) => attendance.student_id === studentId && attendance.section === section && attendance.status,
        );
    };

    const updateAttendance = (attendances, setData, studentId, section, checked) => {
        const updatedAttendance = attendances.filter(
            (attendance) => !(attendance.student_id === studentId && attendance.section === section),
        );

        if (checked) {
            updatedAttendance.push({
                student_id: studentId,
                course_id: props.course.id,
                classroom_id: props.classroom.id,
                section: section,
                status: true,
            });
        }

        setData('attendances', updatedAttendance);
    };

    const getGradeValue = (grades, studentId, category, section) => {
        return (
            grades.find(
                (grade) => grade.student_id === studentId && grade.category === category && grade.section === section,
            )?.grade || ''
        );
    };

    const updateGrade = (grades, setData, studentId, category, section, gradeValue) => {
        const updatedGrades = grades.filter(
            (grade) => !(grade.student_id === studentId && grade.category === category && grade.section === section),
        );

        if (gradeValue) {
            updatedGrades.push({
                student_id: studentId,
                course_id: props.course.id,
                classroom_id: props.classroom.id,
                category: category,
                section: section,
                grade: parseInt(gradeValue, 10) || 0,
            });
        }

        setData('grades', updatedGrades);
    };

    const getAttendanceStudent = (student_id, attendances, section) => {
        return attendances.find((grade) => grade.student_id === student_id && grade.section === section);
    };

    const getGradeStudent = (student_id, grades, category, section) => {
        return grades.find(
            (grade) => grade.student_id === student_id && grade.category === category && grade.section === section,
        );
    };

    const handleSync = () => {
        router.put(route('teachers.classrooms.sync', [props.course.id, props.classroom.id]), data, {
            onSuccess: () => {
                toast.success('Data berhasil disinkronisasi');
            },
        });
    };

    return (
        <div className="flex w-full flex-col pb-32">
            <div className="mb-8 flex flex-col items-start justify-between gap-y-4 lg:flex-row lg:items-center">
                <HeaderTitle
                    title={props.page_settings.title}
                    subtitle={props.page_settings.subtitle}
                    icon={IconDoor}
                />
            </div>
            <Card>
                <CardHeader className="mb-4 p-0">
                    <div className="flex w-full flex-col gap-4 px-6 py-4 lg:flex-row lg:items-center">
                        <Input
                            className="w-full lg:w-1/4"
                            placeholder="Cari nama mahasiswa..."
                            value={params?.search}
                            onChange={(e) => setParams((prev) => ({ ...prev, search: e.target.value }))}
                        />
                        <Button variant="red" size="xl" onClick={(e) => setParams(props.state)}>
                            <IconRefresh className="size-4" />
                            Bersihkan
                        </Button>
                    </div>
                    <div className="space-y-4 px-6 py-4">
                        <Alert variant="destructive">
                            <AlertDescription>
                                Harap isi dengan teliti, data yang disimpan tidak diperbarui
                            </AlertDescription>
                        </Alert>
                        {errors && Object.keys(errors).length > 0 && (
                            <Alert variant="red">
                                <AlertDescription>
                                    {typeof errors === 'string' ? (
                                        errors
                                    ) : (
                                        <ul>
                                            {Object.entries(errors).map(([key, message]) => (
                                                <li key={key}>{message}</li>
                                            ))}
                                        </ul>
                                    )}
                                </AlertDescription>
                            </Alert>
                        )}
                    </div>
                    <ShowFilter params={params} />
                </CardHeader>
                <CardContent className="p-0 [&-td]:whitespace-nowrap [&-td]:px-6 [&-th]:px-6">
                    {students.length === 0 ? (
                        <EmptyState
                            icon={IconDoor}
                            title="Tidak ada mahasiswa"
                            subtitle="Tidak ada mahasiswa yang tergabung di kelas ini"
                        />
                    ) : (
                        <form onSubmit={onHandleSubmit}>
                            <Table className="w-full border">
                                <TableHeader>
                                    <TableRow>
                                        <TableHead rowSpan="2">#</TableHead>
                                        <TableHead rowSpan="2">Nama</TableHead>
                                        <TableHead rowSpan="2">Nomor Induk Mahasiswa</TableHead>
                                        <TableHead colSpan="12" className="border">
                                            Absensi
                                        </TableHead>
                                        <TableHead colSpan="10" className="border">
                                            Tugas
                                        </TableHead>
                                        <TableHead rowSpan="2" className="border">
                                            UTS
                                        </TableHead>
                                        <TableHead rowSpan="2" className="border">
                                            UAS
                                        </TableHead>
                                        <TableHead colSpan="4" className="border">
                                            Total
                                        </TableHead>
                                        <TableHead colSpan="4" className="border">
                                            Persentase Nilai
                                        </TableHead>
                                        <TableHead rowSpan="2" className="border">
                                            Nilai Akhir
                                        </TableHead>
                                        <TableHead rowSpan="2" className="border">
                                            Huruf Mutu
                                        </TableHead>
                                    </TableRow>
                                    <TableRow>
                                        {/* Absensi */}
                                        {Array.from({ length: 12 }).map((_, i) => (
                                            <TableHead key={i} className="border">
                                                {i + 1}
                                            </TableHead>
                                        ))}
                                        {/* Tugas */}
                                        {Array.from({ length: 10 }).map((_, i) => (
                                            <TableHead key={i} className="border">
                                                {i + 1}
                                            </TableHead>
                                        ))}
                                        {/* Total */}
                                        <TableHead className="border">Absen</TableHead>
                                        <TableHead className="border">Tugas</TableHead>
                                        <TableHead className="border">UTS</TableHead>
                                        <TableHead className="border">UAS</TableHead>
                                        {/* Persentase Nilai */}
                                        <TableHead className="border">Absen (10%)</TableHead>
                                        <TableHead className="border">Tugas (20%)</TableHead>
                                        <TableHead className="border">UTS (30%)</TableHead>
                                        <TableHead className="border">UAS (40%)</TableHead>
                                    </TableRow>
                                </TableHeader>
                                <TableBody>
                                    {students.map((student, index) => (
                                        <TableRow key={index}>
                                            <TableCell className="border">{index + 1}</TableCell>
                                            <TableCell>
                                                <div className="flex items-center gap-2">
                                                    <Avatar>
                                                        <AvatarImage src={student.user.avatar} />
                                                        <AvatarFallback>
                                                            {student.user.name.substring(0, 1)}
                                                        </AvatarFallback>
                                                    </Avatar>
                                                    <span>{student.user.name}</span>
                                                </div>
                                            </TableCell>
                                            <TableCell>{student.student_number}</TableCell>
                                            {Array.from({ length: 12 }).map((_, section) => {
                                                const attendance = getAttendanceStudent(
                                                    student.id,
                                                    student.attendances,
                                                    section + 1,
                                                );
                                                return (
                                                    <TableCell key={section}>
                                                        {attendance ? (
                                                            <IconCheck className="size-4 text-green-500" />
                                                        ) : (
                                                            <Checkbox
                                                                id={`attendance_${student.id}_section_${section + 1}`}
                                                                name="attendances"
                                                                checked={isAttendanceChecked(
                                                                    data.attendances,
                                                                    student.id,
                                                                    section + 1,
                                                                )}
                                                                onCheckedChange={(checked) =>
                                                                    updateAttendance(
                                                                        data.attendances,
                                                                        setData,
                                                                        student.id,
                                                                        section + 1,
                                                                        checked,
                                                                    )
                                                                }
                                                            />
                                                        )}
                                                    </TableCell>
                                                );
                                            })}
                                            {Array.from({ length: 10 }).map((_, task) => {
                                                const grade = getGradeStudent(
                                                    student.id,
                                                    student.grades,
                                                    'tugas',
                                                    task + 1,
                                                );
                                                return (
                                                    <TableCell key={task}>
                                                        {grade ? (
                                                            grade.grade
                                                        ) : (
                                                            <>
                                                                <Input
                                                                    className="w-[70px]"
                                                                    value={getGradeValue(
                                                                        data.grades,
                                                                        student.id,
                                                                        'tugas',
                                                                        task + 1,
                                                                    )}
                                                                    onChange={(e) => {
                                                                        updateGrade(
                                                                            data.grades,
                                                                            setData,
                                                                            student.id,
                                                                            'tugas',
                                                                            task + 1,
                                                                            e.target.value,
                                                                        );
                                                                    }}
                                                                />
                                                            </>
                                                        )}
                                                    </TableCell>
                                                );
                                            })}

                                            <TableCell>
                                                {getGradeStudent(student.id, student.grades, 'uts', null) ? (
                                                    getGradeStudent(student.id, student.grades, 'uts', null).grade
                                                ) : (
                                                    <Input
                                                        className="w-[60px]"
                                                        value={getGradeValue(data.grades, student.id, 'uts', null)}
                                                        onChange={(e) => {
                                                            updateGrade(
                                                                data.grades,
                                                                setData,
                                                                student.id,
                                                                'uts',
                                                                null,
                                                                e.target.value,
                                                            );
                                                        }}
                                                    />
                                                )}
                                            </TableCell>
                                            <TableCell>
                                                {getGradeStudent(student.id, student.grades, 'uas', null) ? (
                                                    getGradeStudent(student.id, student.grades, 'uas', null).grade
                                                ) : (
                                                    <Input
                                                        className="w-[60px]"
                                                        value={getGradeValue(data.grades, student.id, 'uas', null)}
                                                        onChange={(e) => {
                                                            updateGrade(
                                                                data.grades,
                                                                setData,
                                                                student.id,
                                                                'uas',
                                                                null,
                                                                e.target.value,
                                                            );
                                                        }}
                                                    />
                                                )}
                                            </TableCell>
                                            <TableCell>{student.total.attendances_count}</TableCell>
                                            <TableCell>{student.total.tasks_count}</TableCell>
                                            <TableCell>{student.total.uts_count}</TableCell>
                                            <TableCell>{student.total.uas_count}</TableCell>
                                            <TableCell>{student.percentage.attendance_percentage}</TableCell>
                                            <TableCell>{student.percentage.task_percentage}</TableCell>
                                            <TableCell>{student.percentage.uts_percentage}</TableCell>
                                            <TableCell>{student.percentage.uas_percentage}</TableCell>
                                            <TableCell>{student.final_score}</TableCell>
                                            <TableCell>{student.letter}</TableCell>
                                        </TableRow>
                                    ))}
                                </TableBody>
                                <TableFooter>
                                    <TableRow>
                                        <TableCell colSpan="37">
                                            <Button variant="orange" type="submit" size="lg" disabled={processing}>
                                                <IconCheck />
                                                Simpan
                                            </Button>
                                        </TableCell>
                                    </TableRow>
                                </TableFooter>
                            </Table>
                        </form>
                    )}
                </CardContent>
            </Card>
        </div>
    );
}

Index.layout = (page) => <AppLayout title={page.props.page_settings.title} children={page} />;
