<?php

namespace App\Http\Controllers\trapp\API;

use App\Classes\KavehNegarClient;
use App\Classes\Sweet\SweetDBFile;
use App\Http\Controllers\common\classes\SweetDateManager;
use App\Http\Controllers\finance\classes\PayDotIr;
use App\Http\Controllers\finance\classes\unsuccessfulPaymentException;
use App\Http\Requests\trapp\villa\trapp_villaAddRequest;
use App\Http\Requests\trapp\villa\trapp_villaUpdateRequest;
use App\models\comments\comments_comment;
use App\models\finance\finance_transaction;
use App\models\placeman\placeman_place;
use App\models\placeman\placeman_placephoto;
use App\models\trapp\trapp_areatype;
use App\models\trapp\trapp_option;
use App\models\trapp\trapp_order;
use App\models\trapp\trapp_ordervillanonfreeoption;
use App\models\trapp\trapp_owningtype;
use App\models\trapp\trapp_structuretype;
use App\models\trapp\trapp_viewtype;
use App\models\trapp\trapp_villa;
use App\Http\Controllers\Controller;
use App\models\trapp\trapp_villanonfreeoption;
use App\models\trapp\trapp_villaowner;
use App\Sweet\SweetQueryBuilder;
use App\Sweet\SweetController;
use App\User;
use Illuminate\Http\Request;
use Bouncer;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Mockery\Exception;
use Morilog\Jalali\Jalalian;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

use Validator;
use Illuminate\Validation\ValidationException;
use App\Http\Requests\trapp\villa\trapp_villaListRequest;

class VillaController extends SweetController
{
    private $ModuleName = 'trapp';
    private $_payDotIrApiCode = "598b9a1afff447b50fbdcfcec969d820";//trapp.sweetsoft.ir
    private $GUESTPRICE = 400000;
//private $_payDotIrApiCode="9b92388e553ba7fd7e3a6d3f28facc45";//JspTutorial.sweetsoft.ir

    public function add(trapp_villaAddRequest $request)
    {
//        if(!Bouncer::can('trapp.villa.insert'))
//            throw new AccessDeniedHttpException();
        $request->validated();

        $Villa = trapp_villa::create([
            'roomcount_num'=>$request->getRoomcountnum(),
            'capacity_num'=>$request->getCapacitynum(),
            'maxguests_num'=>$request->getMaxguestsnum(),
            'structurearea_num'=>$request->getStructureareanum(),
            'totalarea_num'=>$request->getTotalareanum(),
            'placeman_place_fid'=>$request->getPlacemanplace(),
            'is_addedbyowner'=>$request->getAddedbyowner(),
            'viewtype_fid'=>$request->getViewtype(),
            'structuretype_fid'=>$request->getStructuretype(),
            'is_fulltimeservice'=>$request->getFulltimeservice(),
            'timestart_clk'=>$request->getTimestartclk(),
            'owningtype_fid'=>$request->getOwningtype(),
            'areatype_fid'=>$request->getAreatype(),
            'description_te'=>$request->getDescriptionte(),
            'normalprice_prc'=>$request->getNormalpriceprc(),
            'holidayprice_prc'=>$request->getHolidaypriceprc(),
            'weeklyoff_num'=>$request->getWeeklyoffnum(),
            'monthlyoff_num'=>$request->getMonthlyoffnum(),
            'discount_num'=>$request->getDiscountnum(),'deletetime'=>-1]);
        $DocumentphotoiguPathDBFile=new SweetDBFile(SweetDBFile::$GENERAL_DATA_TYPE_IMAGE,$this->ModuleName,'villa','documentphoto',$Villa->id,'jpg');
        $Villa->documentphoto_igu=$DocumentphotoiguPathDBFile->uploadFromRequest($request->getDocumentphotoiguPath());
        $Villa->save();
        return response()->json(['Data'=>$Villa], 201);
    }
    private function _getPhotoLocationFromID($ID)
    {
        return 'img/trapp/villa/villa-' . $ID;
    }
    public function update($id, trapp_villaUpdateRequest $request)
    {
//        if(!Bouncer::can('trapp.villa.edit'))
//            throw new AccessDeniedHttpException();
        $request->setIsUpdate(true);
        $request->validated();


//        $Villa = new trapp_villa();
        $Villa = trapp_villa::find($id);
        $Villa->roomcount_num=$request->getRoomcountnum();
        $Villa->capacity_num=$request->getCapacitynum();
        $Villa->maxguests_num=$request->getMaxguestsnum();
        $Villa->structurearea_num=$request->getStructureareanum();
        $Villa->totalarea_num=$request->getTotalareanum();
        $Villa->placeman_place_fid=$request->getPlacemanplace();
        $Villa->is_addedbyowner=$request->getAddedbyowner();
        $Villa->viewtype_fid=$request->getViewtype();
        $Villa->structuretype_fid=$request->getStructuretype();
        $Villa->is_fulltimeservice=$request->getFulltimeservice();
        $Villa->timestart_clk=$request->getTimestartclk();
        $Villa->owningtype_fid=$request->getOwningtype();
        $Villa->areatype_fid=$request->getAreatype();
        $Villa->description_te=$request->getDescriptionte();
        $Villa->normalprice_prc=$request->getNormalpriceprc();
        $Villa->holidayprice_prc=$request->getHolidaypriceprc();
        $Villa->weeklyoff_num=$request->getWeeklyoffnum();
        $Villa->monthlyoff_num=$request->getMonthlyoffnum();
        $Villa->discount_num=$request->getDiscountnum();
        $DocumentphotoiguPathDBFile=new SweetDBFile(SweetDBFile::$GENERAL_DATA_TYPE_IMAGE,$this->ModuleName,'villa','documentphoto',$Villa->id,'jpg');
        if($request->getDocumentphotoiguPath()!=null)
            $Villa->documentphoto_igu=$DocumentphotoiguPathDBFile->uploadFromRequest($request->getDocumentphotoiguPath());
        $Villa->save();
        return response()->json(['Data'=>$Villa], 202);
    }
    public function list(trapp_villaListRequest $request)
    {
        return $this->_baselist('1', $request);
    }

    public function inactiveList(trapp_villaListRequest $request)
    {
        return $this->_baselist('0', $request);
    }

    private function _baselist($isActive, trapp_villaListRequest $request)
    {
//        Bouncer::allow('admin')->to('trapp.villa.insert');
//        Bouncer::allow('admin')->to('trapp.villa.edit');
//        Bouncer::allow('admin')->to('trapp.villa.list');
//        Bouncer::allow('admin')->to('trapp.villa.view');
//        Bouncer::allow('admin')->to('trapp.villa.delete');
//        Bouncer::allow('admin')->to('comments.comment.insert');
//        Bouncer::allow('admin')->to('comments.comment.edit');
//        Bouncer::allow('admin')->to('comments.comment.list');
//        Bouncer::allow('admin')->to('comments.comment.view');
//        Bouncer::allow('admin')->to('comments.comment.delete');
        $SortsTEST = [];
//        Auth::user()->getAuthIdentifier();
        //if(!Bouncer::can('trapp.villa.list'))
        //throw new AccessDeniedHttpException();
        $SearchText = $request->get('searchtext');
        $UserLatitude = $request->get('userlatitude');
        $UserLongitude = $request->get('userlongitude');
        $NonFreeOptions = json_decode($request->input('nonfreeoptions'));

//        $VillaQuery = trapp_villa::where('id','>=','0');
        $VillaQuery = trapp_villa::join('placeman_place', 'placeman_place.id', '=', 'trapp_villa.placeman_place_fid')
            ->join('placeman_area', 'placeman_area.id', '=', 'placeman_place.area_fid')
            ->join('placeman_city', 'placeman_city.id', '=', 'placeman_area.city_id');
        $VillaQuery = $VillaQuery->join('trapp_villaowner', 'trapp_villaowner.user_fid', '=', 'placeman_place.user_fid');

        $VillaQuery = $VillaQuery->leftJoin('comments_comment', function ($join) {
            $join->on('trapp_villa.id', '=', 'comments_comment.subjectentity_fid')->where('comments_comment.publish_time', '!=', "-1");
        });
        $VillaQuery = $VillaQuery->leftJoin('trapp_order', function ($join) {
            $join->on('trapp_villa.id', '=', 'trapp_order.villa_fid')->where('trapp_order.orderstatus_fid', '=', "2");
        });
//        $VillaQuery = $VillaQuery->where('comments_comment.publish_time', '!=', "-1");

        $VillaQuery = $VillaQuery->groupBy("trapp_villa.id");
        $VillaQuery = $VillaQuery->where('placeman_place.isactive', '=', $isActive);
        $DateStart = $request->get('selectedstartdate');
        $days = $request->get('days');
        $TESTINF = ["DD" => "DDD"];
        if ($DateStart != null && strlen($DateStart > 5) && $days != null && strlen($days) > 0) {
            $TESTINF['ok'] = "DD";
            $DateStart = Jalalian::fromFormat('Y/m/d', $DateStart)->getTimestamp();
            $DayLength = 3600 * 24;
            $DateEnd = (int)$DateStart + (int)$DayLength * ($days - 1);

            $DateQueryText = "(SELECT COUNT(*) FROM trapp_order o WHERE (o.orderstatus_fid=2 OR o.orderstatus_fid=3) and o.villa_fid=trapp_villa.id and o.start_date<=$DateEnd and o.start_date+((o.duration_num-1)*$DayLength)>=$DateStart)=0";
            $TESTINF['dtquery'] = $DateQueryText;
            $VillaQuery = $VillaQuery->whereRaw($DateQueryText);
        }

        if ($NonFreeOptions != null) {
            $NonFreeOptionsArray = get_object_vars($NonFreeOptions);
            $keys = array_keys($NonFreeOptionsArray);
            for ($nfi = 0; $nfi < count($keys); $nfi++) {
                if ($NonFreeOptionsArray[$keys[$nfi]] == true) {
                    $nfo = $keys[$nfi];
                    $nfo = str_replace("option", "", $nfo);
                    $NFOQueryText = "(SELECT COUNT(*) FROM trapp_villanonfreeoption vnfo WHERE vnfo.villa_fid=trapp_villa.id and vnfo.option_fid=$nfo and maxcount_num>0)=1";
                    $VillaQuery = $VillaQuery->whereRaw($NFOQueryText);

                }
            }
        }
        $DistanceField = null;
        if ($UserLatitude != 0 && $UserLatitude != -1 && $UserLongitude != 0 && $UserLongitude != -1) {
            $validator = Validator::make($request->all(), [
                'userlongitude' => 'required|numeric',
                'userlatitude' => 'required|numeric'
            ]);
            if ($validator->fails()) {
//                throw new ValidationException($validator);
                $DistanceField = null;
                $Distance = 0;
            } else {
                $Distance = "
        ( 6371 * acos( cos( radians($UserLatitude) ) * cos( radians( latitude ) ) * cos( radians( longitude ) - radians($UserLongitude) ) + sin( radians($UserLatitude) ) * sin( radians( latitude ) ) ) ) AS `distance`
        ";
                $DistanceField = DB::raw($Distance);
                if ($request->get('distance__sort') != null) {
                    $VillaQuery = $VillaQuery->orderByRaw('distance');
                    $SortsTEST['dist'] = 1;
                }
            }

        }
        if ($request->get('normalpriceprc__sort') != null) {
            $VillaQuery = $VillaQuery->orderBy("normalprice_prc", 'asc');
            $SortsTEST['normalpriceprc__sort'] = 1;
        }
        if ($request->get('maxnormalpriceprc__sort') != null) {
            $VillaQuery = $VillaQuery->orderBy("normalprice_prc", 'desc');
            $SortsTEST['maxnormalpriceprc__sort'] = 1;
        }
        if ($request->get('ratenum__sort') != null) {
            $VillaQuery = $VillaQuery->orderBy("rate", 'desc');
            $SortsTEST['ratenum__sort'] = 1;
        }
        if ($request->get('ordercountnum__sort') != null) {
            $VillaQuery = $VillaQuery->orderBy("ordercount", 'desc');
            $SortsTEST['ordercountnum__sort'] = 1;
        }
        if ($request->get('discountnum__sort') != null) {
            $VillaQuery = $VillaQuery->orderBy("discount_num", 'desc');
            $SortsTEST['discountnum__sort'] = 1;
        }
        /*
//        $VillaQuery =SweetQueryBuilder::WhereLikeIfNotNull($VillaQuery,'roomcount_num',$SearchText);
        $VillaQuery = SweetQueryBuilder::WhereIfNotNull($VillaQuery, 'roomcount_num', $request->get('roomcountnum'));
        $VillaQuery = SweetQueryBuilder::OrderIfNotNull($VillaQuery, 'roomcountnum__sort', 'roomcount_num', $request->get('roomcountnum__sort'));
        $VillaQuery = SweetQueryBuilder::WhereIfNotNull($VillaQuery, 'capacity_num', $request->get('capacitynum'));
        $VillaQuery = SweetQueryBuilder::OrderIfNotNull($VillaQuery, 'capacitynum__sort', 'capacity_num', $request->get('capacitynum__sort'));
        $VillaQuery = SweetQueryBuilder::WhereIfNotNull($VillaQuery, 'maxguests_num', $request->get('maxguestsnum'));
        $VillaQuery = SweetQueryBuilder::OrderIfNotNull($VillaQuery, 'maxguestsnum__sort', 'maxguests_num', $request->get('maxguestsnum__sort'));
        $VillaQuery = SweetQueryBuilder::WhereIfNotNull($VillaQuery, 'totalarea_num', $request->get('totalareanum'));
        $VillaQuery = SweetQueryBuilder::OrderIfNotNull($VillaQuery, 'totalareanum__sort', 'totalarea_num', $request->get('totalareanum__sort'));
        $VillaQuery = SweetQueryBuilder::WhereIfNotNull($VillaQuery, 'structurearea_num', $request->get('structureareanum'));
        $VillaQuery = SweetQueryBuilder::OrderIfNotNull($VillaQuery, 'structureareanum__sort', 'structurearea_num', $request->get('structureareanum__sort'));
        $VillaQuery = SweetQueryBuilder::WhereIfNotNull($VillaQuery, 'is_addedbyowner', $request->get('addedbyowner'));
        $VillaQuery = SweetQueryBuilder::OrderIfNotNull($VillaQuery, 'addedbyowner__sort', 'is_addedbyowner', $request->get('addedbyowner__sort'));
        $VillaQuery = SweetQueryBuilder::WhereIfNotNull($VillaQuery, 'is_fulltimeservice', $request->get('fulltimeservice'));
        $VillaQuery = SweetQueryBuilder::OrderIfNotNull($VillaQuery, 'fulltimeservice__sort', 'is_fulltimeservice', $request->get('fulltimeservice__sort'));
        $VillaQuery = SweetQueryBuilder::WhereIfNotNull($VillaQuery, 'timestart_clk', $request->get('timestartclk'));
        $VillaQuery = SweetQueryBuilder::OrderIfNotNull($VillaQuery, 'timestartclk__sort', 'timestart_clk', $request->get('timestartclk__sort'));
        $VillaQuery = SweetQueryBuilder::WhereLikeIfNotNull($VillaQuery, 'description_te', $request->get('descriptionte'));
        $VillaQuery = SweetQueryBuilder::OrderIfNotNull($VillaQuery, 'descriptionte__sort', 'description_te', $request->get('descriptionte__sort'));
        $VillaQuery = SweetQueryBuilder::WhereIfNotNull($VillaQuery, 'normalprice_prc', $request->get('normalpriceprc'));
        $VillaQuery = SweetQueryBuilder::WhereIfNotNull($VillaQuery, 'holidayprice_prc', $request->get('holidaypriceprc'));
        $VillaQuery = SweetQueryBuilder::OrderIfNotNull($VillaQuery, 'holidaypriceprc__sort', 'holidayprice_prc', $request->get('holidaypriceprc__sort'));
        $VillaQuery = SweetQueryBuilder::WhereIfNotNull($VillaQuery, 'weeklyoff_num', $request->get('weeklyoffnum'));
        $VillaQuery = SweetQueryBuilder::OrderIfNotNull($VillaQuery, 'weeklyoffnum__sort', 'weeklyoff_num', $request->get('weeklyoffnum__sort'));
        $VillaQuery = SweetQueryBuilder::WhereIfNotNull($VillaQuery, 'monthlyoff_num', $request->get('monthlyoffnum'));
        $VillaQuery = SweetQueryBuilder::OrderIfNotNull($VillaQuery, 'monthlyoffnum__sort', 'monthlyoff_num', $request->get('monthlyoffnum__sort'));
        */
        $VillaQuery = SweetQueryBuilder::orderByFields($VillaQuery, $request->getOrderFields());


        $VillaQuery = SweetQueryBuilder::WhereIfNotNull($VillaQuery, 'villa_fid', $request->get('id'));
        $VillaQuery = SweetQueryBuilder::WhereIfNotNull($VillaQuery, 'city_id', $request->get('selectedcityvalue'));
        $VillaQuery = SweetQueryBuilder::WhereIfNotNull($VillaQuery, 'province_id', $request->get('selectedprovincevalue'));
//        $VillaQuery = SweetQueryBuilder::WhereIfNotNull($VillaQuery, 'placeman_place_fid', $request->getPlacemanplace());
        $VillaQuery = SweetQueryBuilder::WhereIfNotNull($VillaQuery, 'viewtype_fid', $request->get('viewtype'));
        $VillaQuery = SweetQueryBuilder::WhereIfNotNull($VillaQuery, 'structuretype_fid', $request->get('structuretype'));
        $VillaQuery = SweetQueryBuilder::WhereIfNotNull($VillaQuery, 'owningtype_fid', $request->get('owningtype'));
        $VillaQuery = SweetQueryBuilder::WhereIfNotNull($VillaQuery, 'areatype_fid', $request->get('areatype'));
        $SelectFields = [DB::raw('avg(comments_comment.rate_num) AS rate'), DB::raw('count(comments_comment.rate_num) AS commentcount'), DB::raw('count(trapp_order.id) AS ordercount'), 'trapp_villaowner.*', 'trapp_villaowner.id AS villaownerid', 'trapp_villa.*'];
        if ($DistanceField != null)
            array_push($SelectFields, $DistanceField);
        $VillasCount = $VillaQuery->get($SelectFields)->count();
        if ($request->isOnlyCount())
            return response()->json(['Data' => [], 'RecordCount' => $VillasCount], 200);
        $Villas = SweetQueryBuilder::setPaginationIfNotNull($VillaQuery,$request->getStartRow(), $request->getPageSize())->get($SelectFields);
        $VillasArray = [];
        for ($i = 0; $i < count($Villas); $i++) {
            $VillasArray[$i] = $Villas[$i]->toArray();
            $PlacemanplaceField = $Villas[$i]->placemanplace();
            $VillasArray[$i]['placemanplacecontent'] = $PlacemanplaceField == null ? '' : $PlacemanplaceField->area()->city()->province()->title . ' ' . $PlacemanplaceField->area()->city()->title;
            $ViewtypeField = $Villas[$i]->viewtype();
            $VillasArray[$i]['viewtypecontent'] = $ViewtypeField == null ? '' : $ViewtypeField->name;
            $StructuretypeField = $Villas[$i]->structuretype();
            $VillasArray[$i]['structuretypecontent'] = $StructuretypeField == null ? '' : $StructuretypeField->name;
            $OwningtypeField = $Villas[$i]->owningtype();
            $VillasArray[$i]['owningtypecontent'] = $OwningtypeField == null ? '' : $OwningtypeField->name;
            $AreatypeField = $Villas[$i]->areatype();
            $VillasArray[$i]['areatypecontent'] = $AreatypeField == null ? '' : $AreatypeField->name;
            $Photos = placeman_placephoto::where('place_fid', '=', $PlacemanplaceField->id)->get();
            $VillasArray[$i]['photo'] = '';
            if (!$Photos->isEmpty()) {
                $thumbPath = '/var/www/html/public/' . $Photos[0]->photo_igu . "thumb";
                if (file_exists($thumbPath))
                    $VillasArray[$i]['photo'] = base64_encode(file_get_contents($thumbPath));
            }

        }
        $Villa = $this->getNormalizedList($VillasArray);
        return response()->json(['Data' => $Villa, 'test' => $TESTINF, 'ss' => [$DateStart], 'ss2' => [$days], 'RecordCount' => $VillasCount], 200);
    }

    private function resizeAndGetBase64($ImageURL)
    {

    }

    public function get($id, Request $request)
    {
        //if(!Bouncer::can('trapp.villa.view'))
        //throw new AccessDeniedHttpException();
        $UserLatitude = $request->get('userlatitude');
        $UserLongitude = $request->get('userlongitude');
        $Villa = trapp_villa::find($id);


        $VillaObjectAsArray = $Villa->toArray();
        $PlacemanplaceObject = $Villa->placemanplace();
        $PlacemanplaceObject = $PlacemanplaceObject == null ? '' : $PlacemanplaceObject;
        $VillaObjectAsArray['placemanplaceinfo'] = $this->getNormalizedItem($PlacemanplaceObject->toArray());
        $area = $PlacemanplaceObject->area();
        $city = $area->city();
        $province = $city->province();
        $Distance = 0;
        if ($UserLatitude != 0 && $UserLatitude != -1 && $UserLongitude != 0 && $UserLongitude != -1) {
            $validator = Validator::make($request->all(), [
                'userlongitude' => 'required|numeric',
                'userlatitude' => 'required|numeric'
            ]);
            if ($validator->fails()) {
            } else {
                $Distance = (6371 * acos(cos(deg2rad($UserLatitude)) * cos(deg2rad($PlacemanplaceObject->latitude)) * cos(deg2rad($PlacemanplaceObject->longitude) - deg2rad($UserLongitude)) + sin(deg2rad($UserLatitude)) * sin(deg2rad($PlacemanplaceObject->latitude))));
            }
        }
        $VillaObjectAsArray['distance'] = $Distance;
        $VillaObjectAsArray['placemanplaceinfo']['areainfo'] = $this->getNormalizedItem($area->toArray());
        $VillaObjectAsArray['placemanplaceinfo']['cityinfo'] = $this->getNormalizedItem($city->toArray());
        $VillaObjectAsArray['placemanplaceinfo']['provinceinfo'] = $this->getNormalizedItem($province->toArray());
        $ViewtypeObject = $Villa->viewtype();
        $ViewtypeObject = $ViewtypeObject == null ? '' : $ViewtypeObject;
        $VillaObjectAsArray['viewtypeinfo'] = $this->getNormalizedItem($ViewtypeObject->toArray());
        $Photos = $Villa->photos();
        $Photos = $Photos == null ? '' : $Photos;
        $VillaObjectAsArray['villaphotos'] = $this->getNormalizedList($Photos->toArray());

        $VillaOwnersObject = $Villa->villaOwners()[0];
        $VillaObjectAsArray['villaowner'] = $this->getNormalizedItem($VillaOwnersObject->toArray());


        $VillaComments = $Villa->publishedComments();
        $VillaCommentsArray = $this->getNormalizedList($VillaComments->toArray());
        for ($ci = 0; $ci < count($VillaCommentsArray); $ci++) {
            $VillaCommentsArray[$ci]['user'] = $VillaComments[$ci]->user();
            $VillaCommentsArray[$ci]['likes'] = $VillaComments[$ci]->likes();
        }
        $VillaObjectAsArray['comments'] = $VillaCommentsArray;
        $VillaObjectAsArray['rate'] = $Villa->rate();

        $VillaOptions = $Villa->options();
        $VillaNonFreeOptions = $Villa->nonfreeoptions();
        $VillaObjectAsArray['options'] = $this->getNormalizedItem($VillaOptions['data']);
        $VillaObjectAsArray['nonfreeoptions'] = $this->getNormalizedItem($VillaNonFreeOptions['data']);

        $StructuretypeObject = $Villa->structuretype();
        $StructuretypeObject = $StructuretypeObject == null ? '' : $StructuretypeObject;
        $VillaObjectAsArray['structuretypeinfo'] = $this->getNormalizedItem($StructuretypeObject->toArray());
        $OwningtypeObject = $Villa->owningtype();
        $OwningtypeObject = $OwningtypeObject == null ? '' : $OwningtypeObject;
        $VillaObjectAsArray['owningtypeinfo'] = $this->getNormalizedItem($OwningtypeObject->toArray());
        $AreatypeObject = $Villa->areatype();
        $AreatypeObject = $AreatypeObject == null ? '' : $AreatypeObject;
        $VillaObjectAsArray['areatypeinfo'] = $this->getNormalizedItem($AreatypeObject->toArray());
        $VillaObjectAsArray['reservedbyuser'] = $this->villaIsReservedByCurrentUser($id);
        $Villa = $this->getNormalizedItem($VillaObjectAsArray);
        return response()->json(['Data' => $Villa], 200);
    }

    private function _GetOrderPrice($VillaID, $NonFreeOptions, $GuestCountNum, $DateStart, $Duration)
    {
//        if(!Bouncer::can('trapp.villa.delete'))
//            throw new AccessDeniedHttpException();
        if (!is_numeric($Duration))
            return ['price' => 0];
        $Villa = trapp_villa::find($VillaID);
        $DateStart = SweetDateManager::getTimeStampFromString($DateStart);
        $DayLength = 3600 * 24;
        $Price = 0;
        for ($Date = $DateStart + 0; $Date < ($DateStart + $Duration * $DayLength); $Date += $DayLength) {
            if (SweetDateManager::isholiday($Date))
                $Price = $Price + $Villa->holidayprice_prc;
            else
                $Price = $Price + $Villa->normalprice_prc;
        }
        $ResidencePriceWithoutDiscount = $Price;
        if ($Duration > 29)
            $Price = $Price * (100 - $Villa->monthlyoff_num) / 100;
        elseif ($Duration > 6)
            $Price = $Price * (100 - $Villa->weeklyoff_num) / 100;

        $ResidencePrice = $Price;
        $OptionsPrice = $this->_getNonFreeOptionsPrice($NonFreeOptions);
        $Price += $OptionsPrice;
        $TotalGuestsPrice = $this->getGuestPrice($GuestCountNum, $Duration);
        $Price += $TotalGuestsPrice;
        return ['price' => $Price, 'options_price' => $OptionsPrice, 'guest_price' => $TotalGuestsPrice, 'residenceprice' => $ResidencePrice, 'residenceprice_without_discount' => $ResidencePriceWithoutDiscount];
    }

    private function _getNonFreeOptionsPrice($NonFreeOptions)
    {
        $OptionsPrice = 0;
        for ($i = 0; $NonFreeOptions != null && $i < count($NonFreeOptions); $i++) {
            if (key_exists('daysnum', $NonFreeOptions[$i]) && $NonFreeOptions[$i]->daysnum > 0) {

                $opt = trapp_villanonfreeoption::find($NonFreeOptions[$i]->villanonfreeoptionid);
                $OptionsPrice += $opt->price_num * $NonFreeOptions[$i]->daysnum;
            }
        }
        return $OptionsPrice;
    }

    private function getGuestPrice($GuestCountNum, $Duration)
    {
        return $this->GUESTPRICE * $GuestCountNum * $Duration;
    }

    public function GetOrderPrice($VillaID, Request $request)
    {
//        if(!Bouncer::can('trapp.villa.delete'))
//            throw new AccessDeniedHttpException();
        $DateStart = $request->get('datestart');
        $Duration = $request->get('duration');
        $Data = $this->_GetOrderPrice($VillaID, [], 0, $DateStart, $Duration);
        return response()->json(['message' => 'ok', 'dt' => [$DateStart], 'Data' => $Data], 201);
    }

    public function reserveByOwner($VillaID, Request $request)
    {
        $DateStart = $request->get('datestart');
        $Duration = $request->get('duration');
        $DateStartTimeStamp = Jalalian::fromFormat('Y/m/d', $DateStart)->getTimestamp();
        $now = time();
        $MinDistanceFromReserveStart = 3600 * 2;
        if ($DateStartTimeStamp - $now < $MinDistanceFromReserveStart)
            return response()->json(['message' => 'تاریخ شروع اقامت گذشته است.', 'Data' => []], 422);
        if (!trapp_villa::getIsVillaReservable($VillaID, $DateStartTimeStamp, $Duration))
            return response()->json(['message' => 'این ویلا در مدت زمات تعیین شده، رزرو شده است.', 'Data' => []], 422);

        $Order = trapp_order::create(['price_prc' => "-1", 'start_date' => $DateStartTimeStamp, 'duration_num' => $Duration,
            'villa_fid' => $VillaID,
            'orderstatus_fid' => 3,
            'user_fid' => Auth::user()->getAuthIdentifier()
        ]);
//            $Transaction = PayDotIr::newTransaction($this->_payDotIrApiCode, $Price, 'VillaReserve', 'http://trapp.sweetsoft.ir/trapp/villa/reserveverify/' . $Order->id);
//            if ($Transaction['transactionid'] == '-2')
//                return response()->json(['message' => 'مبلغ مورد قابل پرداخت نیست', 'Data' => []], 422);

        $Order->save();
        return response()->json(['message' => 'ok', 'dt' => [$DateStart], 'Data' => ['transaction' => null, 'order' => $Order]], 201);
    }

    public function removeReserveByOwner($VillaID, Request $request)
    {
        $OrderId = $request->get('orderid');
        $UserId = Auth::user()->getAuthIdentifier();
        $Order = trapp_order::find($OrderId);
        if ($Order->user_fid != $UserId)
            return response()->json(['message' => 'این سفارش توسط شما ثبت نشده است', 'Data' => []], 422);
        if ($Order->villa_fid != $VillaID)
            return response()->json(['message' => 'این ویلا متعلق به شما نیست', 'Data' => []], 422);
        if ($Order->price_prc > 0)
            return response()->json(['message' => 'شما نمی توانید سفارشات رزرو شده کاربران را لغو کنید', 'Data' => []], 422);

        $Order->delete();
        return response()->json(['message' => 'deleted', 'Data' => []], 202);
    }


    public function StartReservePayment($VillaID, Request $request)
    {
        $DateStart = $request->get('datestart');
        $Duration = $request->get('duration');
        $NonFreeOptions = json_decode($request->input('nonfreeoptions'));
        $GuestCountNum = $request->input('guestcountnum');
//        return response()->json(['message' => 'مبلغ مورد نظر صحیح نیست', 'Data' => $NonFreeOptions], 422);

        $DateStartTimeStamp = Jalalian::fromFormat('Y/m/d', $DateStart)->getTimestamp();
        $now = time();
        $MinDistanceFromReserveStart = 3600 * 2;
        if ($DateStartTimeStamp - $now < $MinDistanceFromReserveStart)
            return response()->json(['message' => 'تاریخ شروع اقامت گذشته است.', 'Data' => []], 422);
        if (!trapp_villa::getIsVillaReservable($VillaID, $DateStartTimeStamp, $Duration))
            return response()->json(['message' => 'این ویلا در مدت زمات تعیین شده، رزرو شده است.', 'Data' => []], 422);
        $Data = $this->_GetOrderPrice($VillaID, $NonFreeOptions, $GuestCountNum, $DateStart, $Duration);

        $Price = $Data['price'];
        $ResidenceTotalPrice = $Data['residenceprice'];
        $ResidencePriceWithoutDiscount = $Data['residenceprice_without_discount'];
        if ($Price > 0) {
            $Order = trapp_order::create(['price_prc' => $Price, 'start_date' => $DateStartTimeStamp, 'duration_num' => $Duration,
                'villa_fid' => $VillaID,
                'orderstatus_fid' => 1,
                'user_fid' => Auth::user()->getAuthIdentifier()
            ]);
            $Transaction = PayDotIr::newTransaction($this->_payDotIrApiCode, $Price, 'VillaReserve', 'http://trapp.sweetsoft.ir/trapp/villa/reserveverify/' . $Order->id);
            if ($Transaction['transactionid'] == '-2')
                return response()->json(['message' => 'مبلغ مورد قابل پرداخت نیست', 'Data' => []], 422);

            $Order->reserve__finance_transaction_fid = $Transaction['finance_transaction']->id;
            $Order->guestcount_num = $GuestCountNum;
            $Order->residencetotalprice_num = $ResidenceTotalPrice;
            $Order->residencepricewithoutdiscount_num = $ResidencePriceWithoutDiscount;
            $Order->guesttotalprice_num = $this->getGuestPrice($GuestCountNum, $Duration);
            $Order->save();
            for ($i = 0; $NonFreeOptions != null && $i < count($NonFreeOptions); $i++) {
                if (key_exists('daysnum', $NonFreeOptions[$i]) && $NonFreeOptions[$i]->daysnum > 0) {

                    $opt = trapp_villanonfreeoption::find($NonFreeOptions[$i]->villanonfreeoptionid);
                    trapp_ordervillanonfreeoption::create(["count_num" => "1", 'days_num' => $NonFreeOptions[$i]->daysnum,
                        'startday_num' => '0', "order_fid" => $Order->id, "villanonfreeoption_fid" => $opt->id,
                        'price_num' => $opt->price_num * $Duration]);
//                    $Price+=$opt->price_num*$Duration;
                }
            }
//            if($GuestCountNum>0)
//                trapp_ordervillanonfreeoption::create(["count_num"=>"1","order_fid"=>$Order->id,"villanonfreeoption_fid"=>$opt->id,'price_num'=>$opt->price_num*$Duration]);

            return response()->json(['message' => 'ok', 'dt' => [$DateStart], 'Data' => ['transaction' => $Transaction, 'order' => $Order]], 201);
        }
        return response()->json(['message' => 'مبلغ مورد نظر صحیح نیست', 'Data' => []], 422);

    }

    private function _getNormalizedName($name)
    {
        $name = str_replace('_', ' ', $name);
        $name = str_replace('-', ' ', $name);
        $name = str_replace('/', ' ', $name);
        $name = str_replace('\\', ' ', $name);
        $name = str_replace('.', ' ', $name);
        $name = str_replace(',', ' ', $name);
        $name = explode(' ', $name)[0];
        return $name;
    }

    public function verifyPaymentAndReserve($OrderID, Request $request)
    {
        $Order = trapp_order::find($OrderID);
        $Villa = trapp_villa::find($Order->villa_fid);
        $OwnerUserID = $Villa->villaOwners()[0]->user_fid;
        $ownerUser = User::find($OwnerUserID);
        $Transaction = $Order->reservefinancetransaction();
        $user_id = $Transaction->user_fid;
        $reserverUser = User::find($user_id);
        $DayLength = 3600 * 24;

        $EndDate = ((int)$Order->start_date) + $DayLength * $Order->duration_num;
        $DateEndTimeStamp = Jalalian::forge($EndDate)->format('Y/m/d');
        $DateStartTimeStamp = Jalalian::forge($Order->start_date)->format('Y/m/d');

//        $opC = new KavehNegarClient("1000800808");


//        $opC->sendTokenMessage($reserverUser->phone, $ownerUser->name, $Villa->id, $DateStartTimeStamp . 'تا' . $DateEndTimeStamp, 'ownerreserve');

//        $opC->sendTokenMessage($ownerUser->phone, $ownerUser->name, $Villa->id, $DateStartTimeStamp . 'تا' . $DateEndTimeStamp, 'ownerreserve');

        if ($Order->orderstatus_fid == 2)
            return view("trapp/Payment", ["success" => false, "message" => "وضعیت تراکنش قبلا ثبت شده و رزرو با موفقیت انجام شده است.", "orderID" => $Order->id, "owner" => $ownerUser, "reserver" => $reserverUser, 'villa' => $Villa]);
//        $DateEndTimeStamp='';

        if (!trapp_villa::getIsVillaReservable($Order->villa_fid, $DateStartTimeStamp, $Order->duration_num))
            return view("trapp/Payment", ["message" => "عملیات رزرو با خطا مواجه شد. در صورتی که مبلغی از حساب شما کم شود، تا 72 ساعت آینده به حساب شما باز خواهد گشت", "orderID" => 0, "owner" => 0, "reserver" => 0, 'villa' => 0]);

        $status = $request->get('status');
        if ($status == 1) {
            $Transaction->status = 3;
            $Transaction->save();
            try {
                $result = PayDotIr::verify($this->_payDotIrApiCode, $Transaction->transactionid);
                if ($result->status == '1') {
                    $Transaction = finance_transaction::create(['amount_prc' => (-1 * $Transaction->amount_prc), 'transactionid' => '-1', 'status' => 3, 'user_fid' => $user_id, 'description_te' => 'Reservation Of Villa ' . $Villa->id]);
                    $Order->orderstatus_fid = 2;
                    $Order->save();
                    $Transaction = finance_transaction::create(['amount_prc' => ($Transaction->amount_prc), 'transactionid' => $Transaction->transactionid, 'status' => 3, 'user_fid' => $OwnerUserID, 'description_te' => 'Money Of Reservation Of ' . $Villa->id]);
//                    $Order->orderstatus_fid = 2;
//                    $Order->save();
                    $opC = new KavehNegarClient("1000800808");
//                    return $ownerUser;
//                    $opC->sendMessage($reserverUser->phone, 'Trapp.ir \r\n'.$reserverUser->name." عزیز، رزرو ویلا با کد $Villa->id با موفقیت انجام شد.");

                    $opC = new KavehNegarClient("1000800808");
                    $opC->sendTokenMessage($reserverUser->phone, $this->_getNormalizedName($reserverUser->name), $Villa->id, $DateStartTimeStamp . 'تا' . $DateEndTimeStamp, 'userreserve');
                    $opC->sendTokenMessage($ownerUser->phone, $this->_getNormalizedName($ownerUser->name), $Villa->id, $DateStartTimeStamp . 'تا' . $DateEndTimeStamp, 'ownerreserve');

//                    $opC->sendMessage($reserverUser->phone, $reserverUser->name . " عزیز، رزرو ویلا با کد " . $Villa->id . ' از تاریخ ' . $DateStartTimeStamp . ' تا تاریخ ' . $DateEndTimeStamp . " با موفقیت انجام شد." . '
//Trapp');
//                    $opC->sendMessage($ownerUser->phone, $ownerUser->name . " عزیز، رزرو ویلای شما با کد " . $Villa->id . ' از تاریخ ' . $DateStartTimeStamp . ' تا تاریخ ' . $DateEndTimeStamp . " با موفقیت انجام شد." . '
//Trapp');
                    return view("trapp/Payment", ["success" => true, "message" => "پرداخت با موفقیت انجام شد.", "orderID" => $OrderID, "owner" => $ownerUser, "reserver" => $reserverUser, 'villa' => $Villa]);

                }
            } catch (unsuccessfulPaymentException $ex) {
                return view("trapp/Payment", ["success" => false, "message" => "پرداخت ناموفق انجام شد." . $ex->getMessage(), "orderID" => $OrderID, "owner" => $ownerUser, "reserver" => $reserverUser, 'villa' => $Villa]);

            }
            return view("trapp/Payment", ["success" => false, "message" => "پرداخت به طور کامل انجام نشد.", "orderID" => $OrderID, "owner" => $ownerUser, "reserver" => $reserverUser, 'villa' => $Villa]);
        }
        return view("trapp/Payment", ["message" => "پرداخت انجام نشد."]);
    }

    public function testPayment($OrderID, Request $request)
    {

        return view("trapp/testPayment", ["data" => $OrderID]);
    }

    public function villaIsReservedByCurrentUser($VillaID)
    {
//        return true;
        $user = Auth::user();
        if ($user != null) {

            $UserPlaces = trapp_order::where('user_fid', '=', $user->id);
            $UserPlaces = $UserPlaces->where('villa_fid', '=', $VillaID);
            $UserPlaces = $UserPlaces->where('orderstatus_fid', '=', 2)->get();
            return !($UserPlaces->isEmpty());
        }
        return false;
    }

    public function getUserFullInfo(Request $request)
    {
        $user = Auth::user();
        $UserPlaces = placeman_place::where('user_fid', '=', $user->id)->get();
        $UserVillas = trapp_villa::getUserVillas($user->id);
        $UserVillaOwners = trapp_villaowner::getUserVillaOwners($user->id);
        return response()->json(['Data' => ['places' => $UserPlaces, 'villas' => $UserVillas, 'owners' => $UserVillaOwners]], 202);

    }

    public function getReservedDaysOfVilla($VillaId, Request $request)
    {
        $days = trapp_villa::getReservedDaysOfVilla($VillaId);
        return response()->json(['Data' => ['dates' => $days]], 202);

    }

    public function getRelatedOptions(Request $request)
    {
        $result = [];
        $result['viewtypes'] = $this->getNormalizedList(trapp_viewtype::all()->toArray());
        $result['structuretypes'] = $this->getNormalizedList(trapp_structuretype::all()->toArray());
        $result['owningtypes'] = $this->getNormalizedList(trapp_owningtype::all()->toArray());
        $result['areatypes'] = $this->getNormalizedList(trapp_areatype::all()->toArray());
        $result['nonfreeoptions'] = $this->getNormalizedList(trapp_option::getNonFreeOptions()->toArray());
        return response()->json(['Data' => $result], 200);

    }

    public function delete($id, Request $request)
    {
        if (!Bouncer::can('trapp.villa.delete'))
            throw new AccessDeniedHttpException();
        $Villa = trapp_villa::find($id);
        $place=$Villa->placeman_place();
        $user=$place->user();
        $user->delete();
        return response()->json(['message' => 'deleted', 'Data' => []], 202);
    }
}