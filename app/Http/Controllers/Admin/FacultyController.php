<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\FacultyResource;
use App\Models\Faculty;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class FacultyController extends Controller
{
    public function index(): Response
    {
        $faculties = Faculty::query()
            ->select(['id', 'name', 'code', 'slug', 'created_at'])
            ->filter(request()->only('search'))
            ->sorting(request()->only(['field', 'direction']))
            ->paginate(request()->load ?? 10);

        return Inertia::render('Admin/Faculties/Index', [
            'page_settings' => [
                'title' => 'Jurusan',
                'subtitle' => 'Menampilkan semua data jurusan yang tersedia pada Politeknik Negeri Kotabaru',
                'breadcrumbs' => [
                    ['name' => 'Jurusan', 'url' => route('admin.faculties.index')]
                ]
            ],
            'faculties' => FacultyResource::collection($faculties)->additional([
                'meta' => [
                    'has_pages' => $faculties->hasPages()
                ]
            ]),
            'state' => [
                'page' => request()->page ?? 1,
                'search' => request()->search ?? '',
                'load' => 10
            ]
        ]);
    }
}
