<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expenses extends Model
{
    use HasFactory;
    protected $table = "expenses";

    public function ExpenseTypeName() {
		return $this->hasOne("App\Models\ExpenseType", "id", "expense_type_id");
	}

	public function BeneficiaryName() {
		return $this->hasOne("App\Models\ExpenseType", "id", "beneficiary_id");
	}

}
