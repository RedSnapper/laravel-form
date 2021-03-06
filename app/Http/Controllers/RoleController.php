<?php

namespace App\Http\Controllers;

use App\Http\Formlets\RoleFormlet;
use App\Models\Category;
use App\Models\Role;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class RoleController extends Controller {

	/**
	 * @var RoleFormlet
	 */
	private $form;

	public function __construct(RoleFormlet $form) {
		$this->form = $form;
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index(Category $category) {
		$roles = Role::orderBy('name');
		if ($category->exists) {
			$roles->where('category_id', $category->id);
			$roles =  $roles->paginate(10);
			return view("role.index",compact('roles','category'));
		}
		return view("role.index");
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return View
	 */
	public function create(Category $category) {$form = $this->form->create(['route' => 'role.store']);
		$form->with('category', $category);
		return $form->render()->with('title', 'New Role');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request) {
		$role = $this->form->store();
		return redirect()->route('role.edit', $role->id);
	}

	/**
	 * @return \Illuminate\Contracts\View\View
	 */
	public function edit($id) {
		$this->form->setKey($id);
		return $this->form->renderWith([
		  'route'  => ['role.update', $id],
		  'method' => 'PUT'
		])
			->with('category',"{$this->form->getModel('role')->category_id}")
			->with('title', "Edit Role: {$this->form->getModel('role')->name}");
	}

	public function update($id) {

		$this->form->setKey($id);
		$role = $this->form->update();
		return redirect()->route('role.edit', $role->id);
	}


	public function destroy(Role $role) {
		$category = $role->category;
		$this->authorize('modify', [$category]);
		$role->delete();
		return redirect()->route('role.index',[$category]);
	}



}
