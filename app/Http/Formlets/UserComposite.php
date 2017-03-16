<?php
/**
 * Created by PhpStorm.
 * User: param
 * Date: 10/03/2017
 * Time: 15:31
 */

namespace App\Http\Formlets;

use App\User;
use Illuminate\Database\Eloquent\Model;

class UserComposite extends Formlet {

	protected $view = "user.composite";
	protected $formView = "user.form";

	public function prepareModels() {
		$user = User::with('profile')->find($this->getKey('user'));
		$this->addModel('user',$user); //needed for the unique email.
		$this->addModel('profile',$user->profile);
		$this->addModel('roles',$user->roles);
	}

		public function prepareForm(){
		if(count($this->keys) == 0) {
			$this->addFormlet('user',UserFormlet::class);
		} else {
			$this->addFormlet('user',UserEmailFormlet::class);
		}
		$this->addFormlet('profile',UserProfileForm::class);
		$this->addFormlet('roles',RoleUserFormlet::class);
	}

	//update
	public function edit(): Model {
		$user = $this->models['user'];
		$user->roles()->sync($this->fields('roles'));
		$user->fill($this->fields('user'))->save();
		$user->profile->fill($this->fields('profile'))->save();
		return $user;
	}

	//new
	public function persist():Model {
		$user = User::create($this->fields('user'));
		$user->profile()->create($this->fields('profile'));
		return $user;
	}

}