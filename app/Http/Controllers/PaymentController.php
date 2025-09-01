<?php

namespace App\Http\Controllers;

use App\Http\Requests\Payment\FilterPaymentRequest;
use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(FilterPaymentRequest $request)
    {
        $this->authorize('viewAny',Payment::class);
        $allPayments = Payment::with(['payable','user'])->filter($request->validated())->paginate(10);
        return self::paginated($allPayments);
    }
    /**
     * Summary of create
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function store(){
        return self::error('unSupported Yet!','error',501);
    }
    /**
     * Display the specified resource.
     */
    public function show(Payment $payment)
    {
        $this->authorize('view',$payment);
        $payment->load(['payable','user']);
        return self::success([$payment]);
    }
    /**
     * Summary of udpate
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function update(){
        return self::error('unSupported Yet!','error',501);
    }
    public function destroy(){
        return self::error('unSupported Yet!','error',501);
    }

}
