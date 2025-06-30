<?php

namespace App\Http\Controllers;

use App\Enums\FeeStatus;
use App\Models\Fee;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;
use Midtrans\Config;
use Midtrans\Snap;

class PaymentController extends Controller
{
    public function create(Request $request): JsonResponse
    {
        Config::$serverKey = config('services.midtrans.server_key');
        Config::$isProduction = config('services.midtrans.is_production');
        Config::$isSanitized = true;
        Config::$is3ds = true;

        $params = [
            'transaction_details' => [
                'order_id' => $request->fee_code,
                'gross_amount' => $request->gross_amount,
            ],
            'customer_details' => [
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
            ]
            ];

        try {
            Fee::updateOrCreate([
                'student_id' => Auth::user()->student?->id,
                'academic_year_id' => activeAcademicYear()->id,
                'semester' => Auth::user()->student?->semester,
            ], [
                'fee_code' => $request->fee_code,
                'student_id' => Auth::user()->student?->id,
                'fee_group_id' => Auth::user()->student?->fee_group_id,
                'academic_year_id' => activeAcademicYear()->id,
                'semester' => Auth::user()->student?->semester,
            ]);

            $snapToken = Snap::getSnapToken($params);
            return response()->json([
                'snapToken' => $snapToken,
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function callback(Request $request): JsonResponse
    {
        $serverKey = config('services.midtrans.server_key');
        $signatureKey = signatureMidtrans(
            $request->fee_code,
            $request->status_code,
            $request->gross_amount,
            $serverKey
        );

        if ($request->signature_key != $signatureKey) {
            return response()->json([
                'error' => 'Unauthorized',
            ], 401);
        }

        $fee = Fee::query()
        ->where('fee_code', $request->fee_code)
        ->first();

        if(!$fee) {
            return response()->json([
                'message' => 'Pembayaran tidak ditemukan',
            ], 404);
        }

        switch ($request->transaction_status) {
            case 'settlement':
                $fee->status = FeeStatus::SUCCESS->value;
                $fee->save();

                return response()->json([
                    'message' => 'Pembayaran melakukan pembayaran',
                ], 200);
                break;
            case 'capture':
                $fee->status = FeeStatus::SUCCESS->value;
                $fee->save();

                return response()->json([
                    'message' => 'Pembayaran melakukan pembayaran',
                ], 200);
                break;
            case 'pending':
                $fee->status = FeeStatus::PENDING->value;
                $fee->save();

                return response()->json([
                    'message' => 'Pembayaran tertunda',
                ], 200);
                break;
            case 'expire':
                $fee->status = FeeStatus::FAILED->value;
                $fee->save();

                return response()->json([
                    'message' => 'Pembayaran kadaluarsa',
                ], 200);
                break;
            case 'cancel':
                $fee->status = FeeStatus::FAILED->value;
                $fee->save();

                return response()->json([
                    'message' => 'Pembayaran dibatalkan',
                ], 200);
                break;
            default:
                return response()->json([
                    'message' => 'Status transaksi tidak diketahui',
                ], 400);
        }
    }

    public function success(): Response
    {
        return Inertia::render('Payments\Success');
    }
}
