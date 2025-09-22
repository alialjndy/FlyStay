<?php

namespace App\Http\Controllers;

use App\Http\Requests\Payment\FilterPaymentRequest;
use App\Http\Requests\Payment\UpdatePaymentRequest;
use App\Models\Payment;
use App\Services\Payment\PaymentService;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    protected $service ;
    public function __construct(PaymentService $service){
        $this->service = $service ;

    }
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
    public function update(UpdatePaymentRequest $request , Payment $payment){
        $this->authorize('update',$payment);
        $info = $this->service->update($payment,$request->validated());
        return $info['status'] === 'success' ? self::success([$info['data']]) : self::error([$info['message']],'error',400);
    }
    public function destroy(){
        return self::error('unSupported Yet!','error',501);
    }

}
