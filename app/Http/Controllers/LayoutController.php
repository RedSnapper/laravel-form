<?php

namespace App\Http\Controllers;

use App\Http\Formlets\LayoutFormlet;
use App\Models\Category;
use App\Models\Layout;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class LayoutController extends Controller {
	/**
	 * @var LayoutFormlet
	 */
	private $form;

	public function __construct(LayoutFormlet $form) {
		$this->form = $form;
		$this->middleware('auth');
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index(Category $category) {
		if ($category->exists) {
			if (Gate::allows('LAYOUTS_ACCESS',$category)) {
				$layouts = Layout::orderBy('name');
				if ($category->exists) {
					$layouts->where('category_id', $category->id);
				}
				$layouts = []; //$layouts->paginate(10);
			}
		} else {
			if (Gate::allows('LAYOUTS_ACCESS')) {
				$layouts = []; //$layouts->paginate(10);
			}
		}
		return view("layout.index", compact('layouts', 'category'));
	}

	/**
	 * Show the form for creating a new resource.
	 */
	public function create(Request $request) {

		$category = $request->get('category');
		$form = $this->form->create(['route' => 'layout.store']);
		$form->with('category', $category);
		return $form->render()->with('title', 'New Layout');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request) {
		$layout = $this->form->store();
		return redirect()->route('layout.edit', $layout->id);
	}

	/**
	 * @return \Illuminate\Contracts\View\View
	 */
	public function edit($id) {
		$this->form->setKey($id);
		return $this->form->renderWith([
			'route'  => ['layout.update', $id],
			'method' => 'PUT'
		])->with('title', "Edit Layout: {$this->form->getModel()->name}");
	}

	public function update($id) {
		$this->form->setKey($id);
		$layout = $this->form->update();
		return redirect()->route('layout.edit', $layout->id);
	}

	/**
	 * @param Layout $layout
	 * @return \Illuminate\Http\RedirectResponse
	 * @throws \Exception
	 * @throws \Illuminate\Auth\Access\AuthorizationException
	 */
	public function destroy(Layout $layout) {
		$category = $layout->category;
		$this->authorize('modify', [$category]);
		$layout->delete();
		return redirect()->route('layout.index',[$category]);
	}
}
