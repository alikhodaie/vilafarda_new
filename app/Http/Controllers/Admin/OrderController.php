<?php

namespace App\Http\Controllers\Admin;

use App\Classes\Error;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateStatusOrderRequest;
use App\Models\Order;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('index', Order::class);

        $orders = Order::query()->search()->latest()->paginate(10)->appends($request->all());

        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $this->authorize('index', Order::class);

        return view('admin.orders.show', compact('order'));
    }

    public function contract(Order $order)
    {
        $this->authorize('showContract', $order);

        return $order->getContractPDF()->download("{$order->id}-contract.pdf");
    }

    public function updateStatus(Order $order, UpdateStatusOrderRequest $request)
    {
        try {
            DB::beginTransaction();

            $data = [
                'status' => $request->get('status')
            ];
            if ($order->status === Order::PENDING && $data['status'] === Order::AWAITING_PAYMENT)
            {
                $data['accepted_at'] = now();
            }

            $order->update($data);

            DB::commit();
            return redirect()->back()->with('success', __('text.success.update_order_status'));
        }
        catch (Exception $e){
            DB::rollBack();
            Error::catch($e, __CLASS__, __FUNCTION__);
            return redirect()->back()->with('danger', __('text.whoops'));
        }
    }
}
