<?php

namespace Modules\MobileApp\Http\Controllers;

use Illuminate\Routing\Controller;
use Modules\MobileApp\Entities\MobileIntro;
use Modules\MobileApp\Http\Requests\StoreMobileIntroRequest;

class VendorMobileIntrosController extends Controller
{
    public function index()
    {
        // first i need to get all intros those i have created
        $mobileIntros = MobileIntro::with("image")
            ->where("type", "vendor")
            ->get();

        return view("mobileapp::intro.vendor.list", compact("mobileIntros"));
    }

    public function create()
    {
        return view("mobileapp::intro.vendor.create");
    }

    public function store(StoreMobileIntroRequest $request)
    {
        $mobileIntro = MobileIntro::create($request->validated());

        return redirect(route("admin.mobile.vendor.intro.all"))
            ->with($mobileIntro ? ["success" => true,
                "msg" => "Mobile Intro created successfully",
                "type" => "success"
            ] : [
                "success" => false,
                "msg" => "failed to create mobile intro",
                "type" => "danger"
            ]);
    }

    public function edit(MobileIntro $mobileIntro)
    {
        return view("mobileapp::intro.vendor.edit", compact("mobileIntro"));
    }

    public function update(StoreMobileIntroRequest $request, MobileIntro $mobileIntro)
    {
        $update = $mobileIntro->update($request->validated());

        return redirect(route("admin.mobile.vendor.intro.all"))
            ->with(
                $update ?
                    [
                        "success" => true,
                        "msg" => "Mobile Intro updated successfully",
                        "type" => "success"
                    ]
                    :
                    [
                        "success" => false,
                        "msg" => "failed to update mobile intro",
                        "type" => "danger"
                    ]
            );
    }

    public function destroy(MobileIntro $mobileIntro)
    {
        $delete = $mobileIntro
            ->delete();

        return response()->json(["type" => $delete ? "success" : "danger" ,"success" => (bool) $delete ?? false, "msg" => $delete ? "Successfully delete mobile intro.vendor." : "Failed to delete mobile intro.vendor."]);
    }
}