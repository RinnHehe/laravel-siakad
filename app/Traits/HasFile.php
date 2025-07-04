<?php

namespace App\Traits;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

trait HasFile
{
    public function upload_file(Request $request, string $column, string $folder): ?string
    {
        return $request->hasFile($column)
            ? $request->file($column)->store($folder)
            : null;
    }

    public function update_file(Request $request, Model $model, string $column, string $folder): ?string
    {
        if ($request->hasFile($column)) {
            $this->delete_file($model, $column);
            return $request->file($column)->store($folder);
        }

        if ($request->has($column) && $request->input($column) === null) {
            $this->delete_file($model, $column);
            return null;
        }

        return $model->$column;
    }

    public function delete_file(Model $model, string $column): void
    {
        if ($model->$column && Storage::exists($model->$column)) {
            Storage::delete($model->$column);
        }
    }
}
