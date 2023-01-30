<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreGroupRequest;
use App\Http\Requests\UpdateGroupRequest;
use App\Models\Group;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class GroupController extends Controller {
    public function index( Request $request ) {
        abort_if ( Gate::denies( 'group_access' ), Response::HTTP_FORBIDDEN, '403 Forbidden' );

        $groups = Group::withCount('banks')->get();

        return view( 'admin.groups.index', [ 'groups' =>$groups ] );
    }

    public function create() {
        abort_if ( Gate::denies( 'group_create' ), Response::HTTP_FORBIDDEN, '403 Forbidden' );

        return view( 'admin.groups.create' );
    }

    public function store( StoreGroupRequest $request ) {
        $group = Group::create( $request->all() );

        return redirect()->route( 'admin.groups.index' );
    }

    public function edit( Group $group ) {
        abort_if ( Gate::denies( 'group_edit' ), Response::HTTP_FORBIDDEN, '403 Forbidden' );

        return view( 'admin.groups.edit', compact( 'group' ) );
    }

    public function update( UpdateGroupRequest $request, Group $group ) {
        $group->update( $request->all() );

        return redirect()->route( 'admin.groups.index' );
    }

    public function show( Group $group ) {
        abort_if ( Gate::denies( 'group_show' ), Response::HTTP_FORBIDDEN, '403 Forbidden' );

        return view( 'admin.groups.show', compact( 'group' ) );
    }

    public function destroy( Group $group ) {
        abort_if ( Gate::denies( 'group_delete' ), Response::HTTP_FORBIDDEN, '403 Forbidden' );

        $group->delete();

        return back();
    }
}
