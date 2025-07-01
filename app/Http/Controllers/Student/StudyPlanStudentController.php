<?php

namespace App\Http\Controllers\Student;

use App\Enums\MessageType;
use App\Enums\StudyPlanStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Student\StudyPlanStudentRequest;
use App\Http\Resources\Admin\ScheduleResource;
use App\Models\StudyPlan;
use Illuminate\Http\Request;
use Inertia\Response;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use App\Http\Resources\Student\StudyPlanStudentResource;
use App\Models\Schedule;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\DB;
use Throwable;

class StudyPlanStudentController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('checkActiveAcademicYear', except:['index']),
            new Middleware('checkFeeStudent', except:['index']),
        ];
    }

    public function index(): Response
    {
        $studyPlans = StudyPlan::query()
            ->select(['id', 'student_id', 'academic_year_id', 'status', 'created_at'])
            ->where('student_id', Auth::user()->student->id)
            ->with(['academicYear'])
            ->latest('created_at')
            ->paginate(request()->load ?? 10);

        return Inertia::render('Students/StudyPlans/Index', [
            'page_settings' => [
                'title' => 'Kartu Rencana Studi',
                'subtitle' => 'Menampilkan semua kartu rencana studi anda',
            ],
            'studyPlans' => StudyPlanStudentResource::collection($studyPlans)->additional([
                'page_settings' => [
                    'meta' => [
                        'has_pages' => $studyPlans->hasPages(),
                    ]
                ]
            ]),
            'state' => [
                'page' => request()->page ?? 1,
                'search' => request()->search ?? '',
                'load' => 10,
            ]
        ]);
    }

    public function create(): Response|RedirectResponse
    {
        if (!activeAcademicYear()) return back();
        $schedules = Schedule::query()
            ->where('faculty_id', Auth::user()->student->faculty_id)
            ->where('department_id', Auth::user()->student->department_id)
            ->where('academic_year_id', activeAcademicYear()->id)
            ->with(['course', 'classroom'])
            ->withCount(['studyPlans as taken_quota' => fn($query) => $query->where('academic_year_id', activeAcademicYear()->id)])
            ->orderByDesc('day_of_week')
            ->get();

        if ($schedules->isEmpty()) {
            flashMessage('Tidak ada jadwal yang tersedia....', 'warning');
            return to_route('students.study.plans.index');
        }

        $studyPlan = StudyPlan::query()
            ->where('student_id', Auth::user()->student->id)
            ->where('academic_year_id', activeAcademicYear()->id)
            ->where('semester', Auth::user()->student->semester)
            ->where('status', '!=', StudyPlanStatus::REJECT)
            ->exists();

        if ($studyPlan) {
            flashMessage('Anda sudah mengajukan KRS', 'warning');
            return to_route('students.study.plans.index');
        }

        return Inertia::render('Students/StudyPlans/Create', [
            'page_settings' => [
                'title' => 'Tambah kartu rencana studi',
                'subtitle' => 'Harap pilih mata kuliah yang sesuai dengan kelas anda',
                'method' => 'POST',
                'action' => route('students.study.plans.store'),
            ],
            'schedules' => ScheduleResource::collection($schedules),
        ]);
    }

    public function store(StudyPlanStudentRequest $request): RedirectResponse
    {
        try {
            DB::beginTransaction();
            $studyPlan = StudyPlan::create([
                'student_id' => Auth::user()->student->id,
                'academic_year_id' => activeAcademicYear()->id,
            ]);

            $studyPlan->schedules()->attach($request->validated()['schedule_id']);
            DB::commit();

            flashMessage('Berhasil mengajukan KRS');
            return to_route('students.study.plans.index');

        } catch (Throwable $e){
            DB::rollBack();
            flashMessage(MessageType::ERROR->message(error: $e->getMessage()), 'error');
            return to_route('students.study.plans.index');
        }
    }

    public function show(StudyPlan $studyPlan): Response
    {
        return Inertia::render('Students/StudyPlans/Show', [
            'page_settings' => [
                'title' => 'Detail Kartu Rencana Studi',
                'subtitle' => 'Menampilkan kartu rencana studi yang sudah anda ajukan',
            ],
            'studyPlan' => new StudyPlanStudentResource(
                $studyPlan->load([
                    'schedules.course',
                    'schedules.classroom',
                    'schedules.academicYear',
                    'academicYear'
                ])
            ),
        ]);
    }
}
