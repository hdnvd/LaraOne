<?php
namespace App\Http\Requests\trapp\villa;
use App\Http\Requests\sweetRequest;
class trapp_villaAddRequest extends trapp_villaUpdateRequest
{
    public function rules()
    {
        $Fields = [
            'placemanplace' => 'required|min:-1|integer',
            'documentphotoigu' => 'required|max:2048',
        ];

        $Fields = array_merge($Fields, parent::rules());
        return $Fields;
    }

    public function messages()
    {
        return [

            'roomcountnum.required' => 'وارد کردن تعداد اتاق اجباری می باشد',
            'roomcountnum.numeric' => 'مقدار تعداد اتاق باید عدد انگلیسی باشد.',
            'capacitynum.required' => 'وارد کردن ظرفیت به نفر اجباری می باشد',
            'capacitynum.numeric' => 'مقدار ظرفیت به نفر باید عدد انگلیسی باشد.',
            'maxguestsnum.required' => 'وارد کردن حداکثر تعداد مهمان اجباری می باشد',
            'maxguestsnum.numeric' => 'مقدار حداکثر تعداد مهمان باید عدد انگلیسی باشد.',
            'structureareanum.required' => 'وارد کردن متراژ بنا اجباری می باشد',
            'structureareanum.numeric' => 'مقدار متراژ بنا باید عدد انگلیسی باشد.',
            'totalareanum.required' => 'وارد کردن متراژ کل اجباری می باشد',
            'totalareanum.numeric' => 'مقدار متراژ کل باید عدد انگلیسی باشد.',
            'placemanplace.required' => 'وارد کردن محل اجباری می باشد',
            'placemanplace.integer' => 'مقدار محل صحیح وارد نشده است.',
            'addedbyowner.required' => 'وارد کردن دارای سند مالکیت به نام کاربر اجباری می باشد',
            'viewtype.required' => 'وارد کردن چشم انداز اجباری می باشد',
            'viewtype.integer' => 'مقدار چشم انداز صحیح وارد نشده است.',
            'structuretype.required' => 'وارد کردن نوع ساختمان اجباری می باشد',
            'structuretype.integer' => 'مقدار نوع ساختمان صحیح وارد نشده است.',
            'fulltimeservice.required' => 'وارد کردن تحویل ۲۴ ساعته اجباری می باشد',
            'timestartclk.required' => 'وارد کردن زمان تحویل/تخلیه اجباری می باشد',
            'owningtype.required' => 'وارد کردن نوع اقامتگاه اجباری می باشد',
            'owningtype.integer' => 'مقدار نوع اقامتگاه صحیح وارد نشده است.',
            'areatype.required' => 'وارد کردن بافت اجباری می باشد',
            'areatype.integer' => 'مقدار بافت صحیح وارد نشده است.',
            'descriptionte.required' => 'وارد کردن توضیحات اجباری می باشد',
            'documentphotoigu.required' => 'وارد کردن سند مالکیت اجباری می باشد',
            'documentphotoigu.max' => 'حجم فایل سند مالکیت باید کمتر از 2 مگابایت باشد',
            'normalpriceprc.required' => 'وارد کردن قیمت در روزهای عادی اجباری می باشد',
            'normalpriceprc.numeric' => 'مقدار قیمت در روزهای عادی باید عدد انگلیسی باشد.',
            'holidaypriceprc.required' => 'وارد کردن قیمت در روزهای تعطیل اجباری می باشد',
            'holidaypriceprc.numeric' => 'مقدار قیمت در روزهای تعطیل باید عدد انگلیسی باشد.',
            'weeklyoffnum.required' => 'وارد کردن تخفیف رزرو بیش از یک هفته اجباری می باشد',
            'weeklyoffnum.numeric' => 'مقدار تخفیف رزرو بیش از یک هفته باید عدد انگلیسی باشد.',
            'monthlyoffnum.required' => 'وارد کردن تخفیف رزرو بیش از یک ماه اجباری می باشد',
            'monthlyoffnum.numeric' => 'مقدار تخفیف رزرو بیش از یک ماه باید عدد انگلیسی باشد.',
        ];
    }
    
    public function authorize()
    {
        return true;
    }
    public function __construct(array $query = array(), array $request = array(), array $attributes = array(), array $cookies = array(), array $files = array(), array $server = array(), $content = null)
    {
        parent::__construct($query, $request, $attributes, $cookies, $files, $server, $content);
        $this->setRequestMethod(sweetRequest::$REQUEST_METHOD_POST);
    }
}