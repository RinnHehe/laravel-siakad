<?php

namespace App\Http\Controllers\Admin;

use App\Enums\MessageType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\FeeGroupRequest;
use App\Http\Resources\Admin\FeeGroupResource;
use App\Models\FeeGroup;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Throwable;

class FeeGroupController extends Controller
{
    public function index(): Response
    {
        $feeGroups = FeeGroup::query()
            ->select('id', 'group', 'amount', 'created_at')
            ->filter(request()->only(['search']))
            ->sorting(request()->only(['field', 'direction']))
            ->paginate(request()->load ?? 10);

        return Inertia::render('Admin/FeeGroups/Index', [
            'page_settings' => [
                'title' => 'Golongan UKT',
                'subtitle' => 'Menampilkan semua golongan UKT yang tersedia pada platform ini',
            ],
            'feeGroups' => FeeGroupResource::collection($feeGroups)->additional([
                'meta' => [
                    'has_pages' => $feeGroups->hasPages(),
                ],
            ]),
            'state' => [
                'page' => request()->page ?? 1,
                'search' => request()->search ?? '',
                'load' => request()->load ?? 10,
                'field' => request()->field ?? 'created_at',
                'direction' => request()->direction ?? 'desc',
            ],
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Admin/FeeGroups/Create', [
            'page_settings' => [
                'title' => 'Tambah Golongan UKT',
                'subtitle' => 'Menambahkan golongan UKT baru ke dalam platform',
                'method' => 'POST',
                'action' => route('admin.fee-groups.store'),
            ],
        ]);
    }

    public function store(FeeGroupRequest $request)
    {
        try {
            $validated = $request->validated();

            FeeGroup::create([
                'group' => $validated['group'],
                'amount' => $validated['amount'],
            ]);

            session()->flash('type', 'success');
            session()->flash('message', MessageType::CREATED->message('Golongan UKT'));

            return Inertia::location(route('admin.fee-groups.index'));
        } catch (Throwable $e) {
            return Inertia::render('Admin/FeeGroups/Create', [
                'page_settings' => [
                    'title' => 'Tambah Golongan UKT',
                    'subtitle' => 'Menambahkan golongan UKT baru ke dalam platform',
                    'method' => 'POST',
                    'action' => route('admin.fee-groups.store'),
                ],
                'errors' => [
                    'group' => $e->getMessage()
                ]
            ]);
        }
    }

    public function edit(FeeGroup $feeGroup): Response
    {
        return Inertia::render('Admin/FeeGroups/Edit', [
            'page_settings' => [
                'title' => 'Edit Golongan UKT',
                'subtitle' => 'Mengubah golongan UKT yang sudah ada pada platform',
                'method' => 'PUT',
                'action' => route('admin.fee-groups.update', $feeGroup),
            ],
            'feeGroup' => $feeGroup,
        ]);
    }

    public function update(FeeGroupRequest $request, FeeGroup $feeGroup)
    {
        try {
            $validated = $request->validated();

            $feeGroup->update([
                'group' => $validated['group'],
                'amount' => $validated['amount'],
            ]);

            session()->flash('type', 'success');
            session()->flash('message', MessageType::UPDATED->message('Golongan UKT'));

            return Inertia::location(route('admin.fee-groups.index'));
        } catch (Throwable $e) {
            return Inertia::render('Admin/FeeGroups/Edit', [
                'page_settings' => [
                    'title' => 'Edit Golongan UKT',
                    'subtitle' => 'Mengubah golongan UKT yang sudah ada pada platform',
                    'method' => 'PUT',
                    'action' => route('admin.fee-groups.update', $feeGroup),
                ],
                'feeGroup' => $feeGroup,
                'errors' => [
                    'group' => $e->getMessage()
                ]
            ]);
        }
    }

    public function destroy(FeeGroup $feeGroup)
    {
        try {
            $feeGroup->delete();

            session()->flash('type', 'success');
            session()->flash('message', MessageType::DELETED->message('Golongan UKT'));

            return Inertia::location(route('admin.fee-groups.index'));
        } catch (Throwable $e) {
            return back()->withErrors([
                'group' => $e->getMessage()
            ]);
        }
    }
}
