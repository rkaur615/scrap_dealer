<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CreateUpdateServiceRequest;
use App\Models\Service;
use App\Http\Resources\ServiceCollection;

class ServiceController extends Controller
{
    public function getServices(Request $request)
    {
        if (!empty($request->service_name)) {
            $products = Service::where('service_name', $request->service_name)->get();
            return new ServiceCollection($products);
        } else {
            return new ServiceCollection(Service::all());
        }
    }

    public function createService(CreateUpdateServiceRequest $request)
    {
        $service = Service::create($request->validated() + ['added_by' => auth()->user()->id]);
        if ($service) {
            return response()->json([
                'message' => __('apiMessage.createService'),
                'success' => true
            ], 200);
        } else {
            return response()->json([
                'error' => __('apiMessage.errorMsg'),
                'success' => false
            ], 400);
        }
    }

    public function deleteService(Service $service)
    {
        $service->delete();
        return response()->json([
            'message' => __('apiMessage.deleteService'),
            'success' => true
        ], 200);
    }

    public function list(Request $request)
    {
        $services = Service::with('user')->paginate();
        return new ServiceCollection($services);
    }
}

