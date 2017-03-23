<?php

namespace App\Http\Formlets;
use Illuminate\Database\Eloquent\Model;

class RoleComposite extends Formlet {

	protected $compositeView = "role.composite";
	protected $formView = "role.form";

	public function prepareForm(){
		$role = $this->addFormlet('role',RoleFormlet::class)
			->setKey($this->key);
		$this->addFormlet('users',Subscriber::class)
			->setModel($role->getModel());
	}

	//update
	public function edit(): Model {
		$role = $this->getFormlet('role')->edit();
		$this->getFormlet('users')->edit();
		return $role;
	}

	//new
	public function persist():Model {
		$role = $this->getFormlet('role')->persist();
		$this->getFormlet('users')->setModel($role)->persist();
		return $role;
	}

}