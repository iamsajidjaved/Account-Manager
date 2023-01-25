<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PermissionsController extends Controller
 {
    public function index()
 {
        abort_if ( Gate::denies( 'permission_access' ), Response::HTTP_FORBIDDEN, '403 Forbidden' );

        $permissions = Permission::all();

        return view( 'admin.permissions.index', compact( 'permissions' ) );
    }
}
