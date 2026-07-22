<?php
namespace App\Domain\CRM\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;
class StoreActivityRequest extends FormRequest {
 public function authorize():bool{return true;}
 public function rules():array{return [
  'type'=>['required','in:note,call,whatsapp,email,meeting,task'],
  'title'=>['required','string','max:160'],'description'=>['nullable','string'],'due_at'=>['nullable','date'],
 ];}
}
