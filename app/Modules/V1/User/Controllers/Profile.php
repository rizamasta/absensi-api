<?php
namespace App\Modules\V1\User\Controllers;
use App\Http\Controllers\Controller;
use App\Modules\V1\User\Models\User;
use App\Modules\V1\User\Models\UserDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Validator;


class Profile extends Controller {

    public function index(Request $req){
        $user = User::select(array(
                                    'user.id',
                                    'user.username',
                                    'user.email',
                                    'user_detail.name',
                                    'user_detail.country_code',
                                    'user_detail.handphone',
                                    'user_detail.address1',
                                    'user_detail.address2',
                                    'user_detail.address3',
                                    'user_detail.print_bupot_flag',
                                    'user_detail.phone',
                                    'user_detail.npak',
                                    'user_detail.register_date',
                                    'user_detail.va_number',
                                    'user_detail.npwp',
                                    'user_detail.company_name',
                                    'user_detail.company_npwp',
                                    'user_detail.nik',
                                    'user_detail.birth_place',
                                    'user_detail.birth_date',
                                    'user_detail.gender',
                                    'user_detail.city',
                                    'provinces.id as province_id',
                                    'provinces.province as province_name'
                                ))
                    ->where('user.id',$req->user->id)
                    ->leftJoin('user_detail','user_detail.id','=','user.id')
                    ->leftJoin('provinces','provinces.id','=','user_detail.province')
                    ->first();
        if($user){
            $user->handphone = str_replace($user->country_code,"",$user->handphone);
        }
        return $this->response('Sukses mengambil data profile',$user);
    }
    public function code(Request $req){
        $countryArray = array(
            array('name'=>'INDONESIA','country_code'=>'+62'),
            array('name'=>'ANDORRA','country_code'=>'+376'),
            array('name'=>'UNITED ARAB EMIRATES','country_code'=>'+971'),
            array('name'=>'AFGHANISTAN','country_code'=>'+93'),
            array('name'=>'ANTIGUA AND BARBUDA','country_code'=>'+1268'),
            array('name'=>'ANGUILLA','country_code'=>'+1264'),
            array('name'=>'ALBANIA','country_code'=>'+355'),
            array('name'=>'ARMENIA','country_code'=>'+374'),
            array('name'=>'NETHERLANDS ANTILLES','country_code'=>'+599'),
            array('name'=>'ANGOLA','country_code'=>'+244'),
            array('name'=>'ANTARCTICA','country_code'=>'+672'),
            array('name'=>'ARGENTINA','country_code'=>'+54'),
            array('name'=>'AMERICAN SAMOA','country_code'=>'+1684'),
            array('name'=>'AUSTRIA','country_code'=>'+43'),
            array('name'=>'AUSTRALIA','country_code'=>'+61'),
            array('name'=>'ARUBA','country_code'=>'+297'),
            array('name'=>'AZERBAIJAN','country_code'=>'+994'),
            array('name'=>'BOSNIA AND HERZEGOVINA','country_code'=>'+387'),
            array('name'=>'BARBADOS','country_code'=>'+1246'),
            array('name'=>'BANGLADESH','country_code'=>'+880'),
            array('name'=>'BELGIUM','country_code'=>'+32'),
            array('name'=>'BURKINA FASO','country_code'=>'+226'),
            array('name'=>'BULGARIA','country_code'=>'+359'),
            array('name'=>'BAHRAIN','country_code'=>'+973'),
            array('name'=>'BURUNDI','country_code'=>'+257'),
            array('name'=>'BENIN','country_code'=>'+229'),
            array('name'=>'SAINT BARTHELEMY','country_code'=>'+590'),
            array('name'=>'BERMUDA','country_code'=>'+1441'),
            array('name'=>'BRUNEI DARUSSALAM','country_code'=>'+673'),
            array('name'=>'BOLIVIA','country_code'=>'+591'),
            array('name'=>'BRAZIL','country_code'=>'+55'),
            array('name'=>'BAHAMAS','country_code'=>'+1242'),
            array('name'=>'BHUTAN','country_code'=>'+975'),
            array('name'=>'BOTSWANA','country_code'=>'+267'),
            array('name'=>'BELARUS','country_code'=>'+375'),
            array('name'=>'BELIZE','country_code'=>'+501'),
            array('name'=>'CANADA','country_code'=>'+1'),
            array('name'=>'COCOS (KEELING) ISLANDS','country_code'=>'+61'),
            array('name'=>'CONGO, THE DEMOCRATIC REPUBLIC OF THE','country_code'=>'+243'),
            array('name'=>'CENTRAL AFRICAN REPUBLIC','country_code'=>'+236'),
            array('name'=>'CONGO','country_code'=>'+242'),
            array('name'=>'SWITZERLAND','country_code'=>'+41'),
            array('name'=>'COTE D IVOIRE','country_code'=>'+225'),
            array('name'=>'COOK ISLANDS','country_code'=>'+682'),
            array('name'=>'CHILE','country_code'=>'+56'),
            array('name'=>'CAMEROON','country_code'=>'+237'),
            array('name'=>'CHINA','country_code'=>'+86'),
            array('name'=>'COLOMBIA','country_code'=>'+57'),
            array('name'=>'COSTA RICA','country_code'=>'+506'),
            array('name'=>'CUBA','country_code'=>'+53'),
            array('name'=>'CAPE VERDE','country_code'=>'+238'),
            array('name'=>'CHRISTMAS ISLAND','country_code'=>'+61'),
            array('name'=>'CYPRUS','country_code'=>'+357'),
            array('name'=>'CZECH REPUBLIC','country_code'=>'+420'),
            array('name'=>'GERMANY','country_code'=>'+49'),
            array('name'=>'DJIBOUTI','country_code'=>'+253'),
            array('name'=>'DENMARK','country_code'=>'+45'),
            array('name'=>'DOMINICA','country_code'=>'+1767'),
            array('name'=>'DOMINICAN REPUBLIC','country_code'=>'+1809'),
            array('name'=>'ALGERIA','country_code'=>'+213'),
            array('name'=>'ECUADOR','country_code'=>'+593'),
            array('name'=>'ESTONIA','country_code'=>'+372'),
            array('name'=>'EGYPT','country_code'=>'+20'),
            array('name'=>'ERITREA','country_code'=>'+291'),
            array('name'=>'SPAIN','country_code'=>'+34'),
            array('name'=>'ETHIOPIA','country_code'=>'+251'),
            array('name'=>'FINLAND','country_code'=>'+358'),
            array('name'=>'FIJI','country_code'=>'+679'),
            array('name'=>'FALKLAND ISLANDS (MALVINAS)','country_code'=>'+500'),
            array('name'=>'MICRONESIA, FEDERATED STATES OF','country_code'=>'+691'),
            array('name'=>'FAROE ISLANDS','country_code'=>'+298'),
            array('name'=>'FRANCE','country_code'=>'+33'),
            array('name'=>'GABON','country_code'=>'+241'),
            array('name'=>'UNITED KINGDOM','country_code'=>'+44'),
            array('name'=>'GRENADA','country_code'=>'+1473'),
            array('name'=>'GEORGIA','country_code'=>'+995'),
            array('name'=>'GHANA','country_code'=>'+233'),
            array('name'=>'GIBRALTAR','country_code'=>'+350'),
            array('name'=>'GREENLAND','country_code'=>'+299'),
            array('name'=>'GAMBIA','country_code'=>'+220'),
            array('name'=>'GUINEA','country_code'=>'+224'),
            array('name'=>'EQUATORIAL GUINEA','country_code'=>'+240'),
            array('name'=>'GREECE','country_code'=>'+30'),
            array('name'=>'GUATEMALA','country_code'=>'+502'),
            array('name'=>'GUAM','country_code'=>'+1671'),
            array('name'=>'GUINEA-BISSAU','country_code'=>'+245'),
            array('name'=>'GUYANA','country_code'=>'+592'),
            array('name'=>'HONG KONG','country_code'=>'+852'),
            array('name'=>'HONDURAS','country_code'=>'+504'),
            array('name'=>'CROATIA','country_code'=>'+385'),
            array('name'=>'HAITI','country_code'=>'+509'),
            array('name'=>'HUNGARY','country_code'=>'+36'),
            array('name'=>'IRELAND','country_code'=>'+353'),
            array('name'=>'ISRAEL','country_code'=>'+972'),
            array('name'=>'ISLE OF MAN','country_code'=>'+44'),
            array('name'=>'INDIA','country_code'=>'+91'),
            array('name'=>'IRAQ','country_code'=>'+964'),
            array('name'=>'IRAN, ISLAMIC REPUBLIC OF','country_code'=>'+98'),
            array('name'=>'ICELAND','country_code'=>'+354'),
            array('name'=>'ITALY','country_code'=>'+39'),
            array('name'=>'JAMAICA','country_code'=>'+1876'),
            array('name'=>'JORDAN','country_code'=>'+962'),
            array('name'=>'JAPAN','country_code'=>'+81'),
            array('name'=>'KENYA','country_code'=>'+254'),
            array('name'=>'KYRGYZSTAN','country_code'=>'+996'),
            array('name'=>'CAMBODIA','country_code'=>'+855'),
            array('name'=>'KIRIBATI','country_code'=>'+686'),
            array('name'=>'COMOROS','country_code'=>'+269'),
            array('name'=>'SAINT KITTS AND NEVIS','country_code'=>'+1869'),
            array('name'=>'KOREA DEMOCRATIC PEOPLES REPUBLIC OF','country_code'=>'+850'),
            array('name'=>'KOREA REPUBLIC OF','country_code'=>'+82'),
            array('name'=>'KUWAIT','country_code'=>'+965'),
            array('name'=>'CAYMAN ISLANDS','country_code'=>'+1345'),
            array('name'=>'KAZAKSTAN','country_code'=>'+7'),
            array('name'=>'LAO PEOPLES DEMOCRATIC REPUBLIC','country_code'=>'+856'),
            array('name'=>'LEBANON','country_code'=>'+961'),
            array('name'=>'SAINT LUCIA','country_code'=>'+1758'),
            array('name'=>'LIECHTENSTEIN','country_code'=>'+423'),
            array('name'=>'SRI LANKA','country_code'=>'+94'),
            array('name'=>'LIBERIA','country_code'=>'+231'),
            array('name'=>'LESOTHO','country_code'=>'+266'),
            array('name'=>'LITHUANIA','country_code'=>'+370'),
            array('name'=>'LUXEMBOURG','country_code'=>'+352'),
            array('name'=>'LATVIA','country_code'=>'+371'),
            array('name'=>'LIBYAN ARAB JAMAHIRIYA','country_code'=>'+218'),
            array('name'=>'MOROCCO','country_code'=>'+212'),
            array('name'=>'MONACO','country_code'=>'+377'),
            array('name'=>'MOLDOVA, REPUBLIC OF','country_code'=>'+373'),
            array('name'=>'MONTENEGRO','country_code'=>'+382'),
            array('name'=>'SAINT MARTIN','country_code'=>'+1599'),
            array('name'=>'MADAGASCAR','country_code'=>'+261'),
            array('name'=>'MARSHALL ISLANDS','country_code'=>'+692'),
            array('name'=>'MACEDONIA, THE FORMER YUGOSLAV REPUBLIC OF','country_code'=>'+389'),
            array('name'=>'MALI','country_code'=>'+223'),
            array('name'=>'MYANMAR','country_code'=>'+95'),
            array('name'=>'MONGOLIA','country_code'=>'+976'),
            array('name'=>'MACAU','country_code'=>'+853'),
            array('name'=>'NORTHERN MARIANA ISLANDS','country_code'=>'+1670'),
            array('name'=>'MAURITANIA','country_code'=>'+222'),
            array('name'=>'MONTSERRAT','country_code'=>'+1664'),
            array('name'=>'MALTA','country_code'=>'+356'),
            array('name'=>'MAURITIUS','country_code'=>'+230'),
            array('name'=>'MALDIVES','country_code'=>'+960'),
            array('name'=>'MALAWI','country_code'=>'+265'),
            array('name'=>'MEXICO','country_code'=>'+52'),
            array('name'=>'MALAYSIA','country_code'=>'+60'),
            array('name'=>'MOZAMBIQUE','country_code'=>'+258'),
            array('name'=>'NAMIBIA','country_code'=>'+264'),
            array('name'=>'NEW CALEDONIA','country_code'=>'+687'),
            array('name'=>'NIGER','country_code'=>'+227'),
            array('name'=>'NIGERIA','country_code'=>'+234'),
            array('name'=>'NICARAGUA','country_code'=>'+505'),
            array('name'=>'NETHERLANDS','country_code'=>'+31'),
            array('name'=>'NORWAY','country_code'=>'+47'),
            array('name'=>'NEPAL','country_code'=>'+977'),
            array('name'=>'NAURU','country_code'=>'+674'),
            array('name'=>'NIUE','country_code'=>'+683'),
            array('name'=>'NEW ZEALAND','country_code'=>'+64'),
            array('name'=>'OMAN','country_code'=>'+968'),
            array('name'=>'PANAMA','country_code'=>'+507'),
            array('name'=>'PERU','country_code'=>'+51'),
            array('name'=>'FRENCH POLYNESIA','country_code'=>'+689'),
            array('name'=>'PAPUA NEW GUINEA','country_code'=>'+675'),
            array('name'=>'PHILIPPINES','country_code'=>'+63'),
            array('name'=>'PAKISTAN','country_code'=>'+92'),
            array('name'=>'POLAND','country_code'=>'+48'),
            array('name'=>'SAINT PIERRE AND MIQUELON','country_code'=>'+508'),
            array('name'=>'PITCAIRN','country_code'=>'+870'),
            array('name'=>'PUERTO RICO','country_code'=>'+1'),
            array('name'=>'PORTUGAL','country_code'=>'+351'),
            array('name'=>'PALAU','country_code'=>'+680'),
            array('name'=>'PARAGUAY','country_code'=>'+595'),
            array('name'=>'QATAR','country_code'=>'+974'),
            array('name'=>'ROMANIA','country_code'=>'+40'),
            array('name'=>'SERBIA','country_code'=>'+381'),
            array('name'=>'RUSSIAN FEDERATION','country_code'=>'+7'),
            array('name'=>'RWANDA','country_code'=>'+250'),
            array('name'=>'SAUDI ARABIA','country_code'=>'+966'),
            array('name'=>'SOLOMON ISLANDS','country_code'=>'+677'),
            array('name'=>'SEYCHELLES','country_code'=>'+248'),
            array('name'=>'SUDAN','country_code'=>'+249'),
            array('name'=>'SWEDEN','country_code'=>'+46'),
            array('name'=>'SINGAPORE','country_code'=>'+65'),
            array('name'=>'SAINT HELENA','country_code'=>'+290'),
            array('name'=>'SLOVENIA','country_code'=>'+386'),
            array('name'=>'SLOVAKIA','country_code'=>'+421'),
            array('name'=>'SIERRA LEONE','country_code'=>'+232'),
            array('name'=>'SAN MARINO','country_code'=>'+378'),
            array('name'=>'SENEGAL','country_code'=>'+221'),
            array('name'=>'SOMALIA','country_code'=>'+252'),
            array('name'=>'SURINAME','country_code'=>'+597'),
            array('name'=>'SAO TOME AND PRINCIPE','country_code'=>'+239'),
            array('name'=>'EL SALVADOR','country_code'=>'+503'),
            array('name'=>'SYRIAN ARAB REPUBLIC','country_code'=>'+963'),
            array('name'=>'SWAZILAND','country_code'=>'+268'),
            array('name'=>'TURKS AND CAICOS ISLANDS','country_code'=>'+1649'),
            array('name'=>'CHAD','country_code'=>'+235'),
            array('name'=>'TOGO','country_code'=>'+228'),
            array('name'=>'THAILAND','country_code'=>'+66'),
            array('name'=>'TAJIKISTAN','country_code'=>'+992'),
            array('name'=>'TOKELAU','country_code'=>'+690'),
            array('name'=>'TIMOR-LESTE','country_code'=>'+670'),
            array('name'=>'TURKMENISTAN','country_code'=>'+993'),
            array('name'=>'TUNISIA','country_code'=>'+216'),
            array('name'=>'TONGA','country_code'=>'+676'),
            array('name'=>'TURKEY','country_code'=>'+90'),
            array('name'=>'TRINIDAD AND TOBAGO','country_code'=>'+1868'),
            array('name'=>'TUVALU','country_code'=>'+688'),
            array('name'=>'TAIWAN, PROVINCE OF CHINA','country_code'=>'+886'),
            array('name'=>'TANZANIA, UNITED REPUBLIC OF','country_code'=>'+255'),
            array('name'=>'UKRAINE','country_code'=>'+380'),
            array('name'=>'UGANDA','country_code'=>'+256'),
            array('name'=>'UNITED STATES','country_code'=>'+1'),
            array('name'=>'URUGUAY','country_code'=>'+598'),
            array('name'=>'UZBEKISTAN','country_code'=>'+998'),
            array('name'=>'HOLY SEE (VATICAN CITY STATE)','country_code'=>'+39'),
            array('name'=>'SAINT VINCENT AND THE GRENADINES','country_code'=>'+1784'),
            array('name'=>'VENEZUELA','country_code'=>'+58'),
            array('name'=>'VIRGIN ISLANDS, BRITISH','country_code'=>'+1284'),
            array('name'=>'VIRGIN ISLANDS, U.S.','country_code'=>'+1340'),
            array('name'=>'VIET NAM','country_code'=>'+84'),
            array('name'=>'VANUATU','country_code'=>'+678'),
            array('name'=>'WALLIS AND FUTUNA','country_code'=>'+681'),
            array('name'=>'SAMOA','country_code'=>'+685'),
            array('name'=>'KOSOVO','country_code'=>'+381'),
            array('name'=>'YEMEN','country_code'=>'+967'),
            array('name'=>'MAYOTTE','country_code'=>'+262'),
            array('name'=>'SOUTH AFRICA','country_code'=>'+27'),
            array('name'=>'ZAMBIA','country_code'=>'+260'),
            array('name'=>'ZIMBABWE','country_code'=>'+263')
        );
        return $this->response('Sukses mengambil data kode negara',$countryArray);
    }
    public function update(Request $req){
        $body =(Object)$req->json()->all();
        $rules= array(
            'username'=>'required',
            'email'=>'required|email',
            'name' => 'required',
            'handphone'=>'required',
            'country_code'=>'required',
            'address1'=>'required',
            'province'=>'required|numeric',
         );
        $message = array(
            'country_code.required' =>'Kode negara tidak boleh kosong',     
            'username.required'=>'Username tidak boleh kosong',
            'email.required'=>'Email tidak boleh kosong',
            'email.email' =>'Format email salah',
            'name.required' => 'Nama Tidak boleh kosong',
            'handphone.required'=>'Nomor Handphone dibutuhkan',
            'address1.required'=>'Alamat harus diisi',
            'province.required'=>'Provinsi tidak boleh kosong',
            'province.numeric'=>'Tipe provinsi salah',
        );
        $validator = Validator::make($req->json()->all(),$rules,$message);

        if($validator->passes()){
            $detail = UserDetail::where('id',$req->user->id)->first();
            if($detail->handphone != $body->handphone){
                if(strrpos($body->handphone,"0",0)){
                    $handphone = ltrim($body->handphone, "0");
                    $va_number = $handphone;
                 }
                else{
                    $handphone = ltrim($body->handphone, "0");
                    $va_number = $handphone;
                }
            }
            else{
                $handphone = $detail->handphone;
                $va_number = $detail->va_number;
            }
            $checkuser = User::where('username',$body->username)->where('id','!=',$req->user->id)->first();
            $checkemail = User::where('email',$body->email)->where('id','!=',$req->user->id)->first();
            if($checkuser && $checkemail){
                return $this->response('Username dan Email sudah terpakai',array(),403);
            }
            else if($checkuser){
                return $this->response('Username sudah terpakai',array(),403);
            }
            else if($checkemail){
                return $this->response('Email sudah terpakai',array(),403);
            }
            else{
                $cred = array();
                if($body->username != $req->user->username && $body->email != $req->user->email ){
                    $cred = array(
                                'username'=>$body->username,
                                'email' =>$body->email
                            );
                }
                else if($body->username != $req->user->username){
                    $cred = array(
                        'username'=>$body->username,
                        'email' =>$body->email
                    );
                }
                else if($body->email != $req->user->email){
                    $cred = array(
                        'username'=>$body->username,
                        'email' =>$body->email
                    );
                }
                else{
                    $cred = array();
                }

                if(!empty($cred)){
                    User::where('id',$req->user->id)->update($cred);
                }
                $cc = !empty($body->country_code)?$body->country_code:'+62';
                $data_detail = array(
                    'name' => $body->name,
                    'country_code' => $cc,
                    'handphone' => $cc.$handphone,
                    'phone' => $body->phone,
                    'va_number' => $va_number,
                    'npwp' => $body->npwp,
                    'company_flag' => $body->company_flag?$body->company_flag:0,
                    'company_name' => $body->company_name,
                    'company_npwp' => $body->company_npwp,
                    'NIK' => $body->nik,
                    'birth_place' => $body->birth_place,
                    'birth_date' => !empty($body->birth_date)?$body->birth_date:NULL,
                    'gender' => $body->gender,
                    'address1' => $body->address1,
                    'province' => $body->province,
                    'city' => $body->city,
                    'print_bupot_flag' => !empty($body->print_bupot_flag)?$body->print_bupot_flag:0,
                 );
                 UserDetail::where('id',$req->user->id)->update($data_detail);
                 return $this->response('Berhasil memperbarui profile');
            }
        }
        else{
            return $this->response('Data yang dikirim tidak valid',$validator->errors(),400);
        }
    }

    public function changePassword(Request $req){
        $body =(Object)$req->json()->all();
        $rules= array(
            'old_password' => 'required',
            'new_password' => 'required',
            'confirm_password' => 'required',
         );
        $message = array(
            'old_password.required' =>'Password lama dibutuhkan',
            'new_password.required' =>'Mohon isikan password baru anda',
            'confirm_password.required' =>'Mohon ulangi password baru',
        );
        $validator = Validator::make($req->json()->all(),$rules,$message);
        if($validator->passes()){
            if($body->new_password == $body->confirm_password){
                $user_cp = User::where('id','=',$req->user->id)->first();
                if(Hash::check($body->old_password,$user_cp->password_hash)){
                    $password =Hash::make($body->confirm_password);
                    User::where('id',$req->user->id)->update(array('password_hash'=>$password));
                    return $this->response('Password anda berhasil diperbarui');
                }
                else{
                    return $this->response('Password lama salah',[],403);
                }
            }
            else{
                return $this->response('Data yang dikirim tidak valid',array("confirm_password"=>array("Pengulangan password baru tidak sama")),400);
            }
        }
        else{
            return $this->response('Data yang dikirim tidak valid',$validator->errors(),400);
        }
    }
}