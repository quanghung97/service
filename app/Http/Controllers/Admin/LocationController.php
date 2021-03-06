<?php

namespace App\Http\Controllers\Admin;

use File;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreLocationRequest;
use App\Http\Requests\UpdateLocationRequest;
use App\Repositories\Facades\StatusRepository;
use App\Repositories\Facades\LocationRepository;
use App\Repositories\Facades\TypeServiceRepository;

class LocationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $keyword = $request->get('search');
        $perPage = config('paginate.perPage');

        if (!empty($keyword)) {
            $location = LocationRepository::where('name', 'LIKE', "%$keyword%")
                ->latest()->paginate($perPage);
        } else {
            $location = LocationRepository::latest()->paginate($perPage);
        }

        return view('admin.location.index', compact('location'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $typeServices = TypeServiceRepository::get()->pluck('name', 'name');
        $statuses = StatusRepository::get()->pluck('name', 'id');

        return view('admin.location.create', compact('typeServices', 'statuses'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreLocationRequest $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(StoreLocationRequest $request)
    {
        $requestData = $request->except(['image', 'typeServices']);
        $requestData['status_id'] = $request->status;
        $location = LocationRepository::create($requestData);
        $location->assignService($request->typeServices);
        LocationRepository::storeImage($location, $request->image);

        return redirect('admin/location')->with('flash_message', __('location.notification.add'));
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $location = LocationRepository::with('typeServices')->findOrFail($id);
        $typeServices = $location->typeServices->pluck('name')->toArray();
        $status = $location->status;

        return view('admin.location.show', compact('location', 'typeServices', 'status'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $location = LocationRepository::with('status')->findOrFail($id);
        $typeServices = TypeServiceRepository::get()->pluck('name', 'name');
        $statuses = StatusRepository::get()->pluck('name', 'id');

        return view('admin.location.edit', compact('location', 'typeServices', 'statuses'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateLocationRequest $request
     * @param int                   $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateLocationRequest $request, $id)
    {
        $requestData = $request->except(['image', 'typeServices']);
        $requestData['status_id'] = $request->status;
        $location = LocationRepository::with('typeServices')->findOrFail($id);
        $location->update($requestData);
        $location->syncService($request->typeServices);
        if ($request->image != null) {
            LocationRepository::storeImage($location, $request->image);
        }

        return redirect('admin/location')->with('flash_message', __('location.notification.update'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $location = LocationRepository::findOrFail($id);
        File::delete($location->link_image);
        LocationRepository::destroy($id);

        return redirect('admin/location')->with('flash_danger', __('location.notification.delete'));
    }
}
