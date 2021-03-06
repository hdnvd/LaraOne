<?php
namespace App\Http\Controllers\sas\API;
use App\Http\Controllers\sas\Classes\Unit;
use App\models\sas\sas_device;
use App\Http\Controllers\Controller;
use App\models\sas\sas_devicetype;
use App\models\sas\sas_request;
use App\models\sas\sas_status;
use App\models\sas\sas_unit;
use App\Sweet\SweetQueryBuilder;
use App\Sweet\SweetController;
use Illuminate\Http\Request;
use Bouncer;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class DeviceController extends SweetController
{

	public function add(Request $request)
    {
//        if(!Bouncer::can('sas.device.insert'))
//            throw new AccessDeniedHttpException();
    
		$InputName=$request->input('name');
		$InputDevicetype=$request->input('devicetype');
		$InputCode=$request->input('code');
		$InputNotete=$request->input('notete');
//		$InputOwnerunit=$request->input('ownerunit');
        $UnitInfo=Unit::getUserUnitAndType();
        $Unit=$UnitInfo["unit"];
		$Device = sas_device::create(['name'=>$InputName,'devicetype_fid'=>$InputDevicetype,'code'=>$InputCode,'note_te'=>$InputNotete,'owner__unit_fid'=>$Unit->id,'deletetime'=>-1]);
		return response()->json(['Data'=>$Device], 201);
	}
	public function update($id,Request $request)
    {
//        if(!Bouncer::can('sas.device.edit'))
//            throw new AccessDeniedHttpException();
    
        $InputName=$request->get('name');
        $InputDevicetype=$request->get('devicetype');
        $InputCode=$request->get('code');
        $InputNotete=$request->get('notete');
        $Device = new sas_device();
        $Device = $Device->find($id);
        $Device->name=$InputName;
        $Device->devicetype_fid=$InputDevicetype;
        $Device->code=$InputCode;
        $Device->note_te=$InputNotete;
        $Device->save();
        return response()->json(['Data'=>$Device], 202);
    }
    public function listUserDevices(Request $request)
    {
//        Bouncer::allow('admin')->to('sas.device.insert');
//        Bouncer::allow('admin')->to('sas.device.edit');
//        Bouncer::allow('admin')->to('sas.device.list');
//        Bouncer::allow('admin')->to('sas.device.view');
//        Bouncer::allow('admin')->to('sas.device.delete');


        //if(!Bouncer::can('sas.device.list'))
            //throw new AccessDeniedHttpException();
        $UnitInfo=Unit::getUserUnitAndType();
        $Unit=$UnitInfo["unit"];
        $DeviceQuery =SweetQueryBuilder::WhereLikeIfNotNull($this->getDeviceQuery(),'owner__unit_fid',$Unit->id);
        return $this->listDevices($DeviceQuery,$request);
    }
    private function getDeviceQuery()
    {
        $lastRequest=DB::raw('(SELECT device_fid,id FROM `sas_request` WHERE fullsend_time>0 AND finalcommit_time=0 ORDER BY `id` desc limit 0,1) lastincompleterequest');
        $DeviceQuery = sas_device::leftJoin($lastRequest,function($join) {
            $join->on('lastincompleterequest.device_fid', '=', 'sas_device.id');
        });
        return $DeviceQuery;
    }
    public function list(Request $request)
    {
        //if(!Bouncer::can('sas.device.list'))
        //throw new AccessDeniedHttpException();
        $UnitInfo=Unit::getUserUnitAndType();
        $Unit=$UnitInfo["unit"];

        return $this->listDevices($this->getDeviceQuery(),$request);
    }

    private function listDevices($DeviceQuery,Request $request)
    {
        $DeviceQuery =SweetQueryBuilder::WhereLikeIfNotNull($DeviceQuery,'name',$request->get('name'));
        $DeviceQuery =SweetQueryBuilder::WhereLikeIfNotNull($DeviceQuery,'devicetype_fid',$request->get('devicetype'));
        $DeviceQuery =SweetQueryBuilder::WhereLikeIfNotNull($DeviceQuery,'code',$request->get('code'));
        $DeviceQuery =SweetQueryBuilder::WhereLikeIfNotNull($DeviceQuery,'note_te',$request->get('notete'));
        $DeviceQuery =SweetQueryBuilder::WhereLikeIfNotNull($DeviceQuery,'owner__unit_fid',$request->get('ownerunit'));

        if($request->get('isactive')!=null)
        {
            $isact=$request->get('isactive')==1?true:false;
            if($isact)
            $DeviceQuery=$DeviceQuery->whereRaw("ISNULL(lastincompleterequest.id)");
            else
            $DeviceQuery=$DeviceQuery->whereRaw("NOT ISNULL(lastincompleterequest.id)");
        }
        $DeviceQuery->groupBy("sas_device.id");
        $SelectFields=['sas_device.*',DB::raw('(ISNULL(lastincompleterequest.id)) isactive')];
        $DeviceCount=$DeviceQuery->get(['sas_device.*'])->count();
        $Devices = SweetQueryBuilder::setPaginationIfNotNull($DeviceQuery, $request->get('__startrow'), $request->get('__pagesize'))->get($SelectFields);

        $DevicesArray=[];
        for($i=0;$i<count($Devices);$i++)
        {
            $DevicesArray[$i]=$Devices[$i]->toArray();
            $DevicetypeField=$Devices[$i]->devicetype();
            $DevicesArray[$i]['devicetypecontent']=$DevicetypeField==null?'':$DevicetypeField->name;
            $OwnerunitField=$Devices[$i]->ownerunit();
            $DevicesArray[$i]['ownerunitcontent']=$OwnerunitField==null?'':$OwnerunitField->name;
//            $DevicesArray[$i]['isactive']=$Devices[$i]->isactive();
            $lastReq=$Devices[$i]->lastrequest();
            $DevicesArray[$i]['lastrequest']=$lastReq==null?'':$lastReq->id;
        }
        $Device = $this->getNormalizedList($DevicesArray);
        return response()->json(['Data'=>$Device,'RecordCount'=>$DeviceCount], 200);
    }

    public function get($id,Request $request)
    {
        //if(!Bouncer::can('sas.device.view'))
        //throw new AccessDeniedHttpException();
        $Device=sas_device::find($id);
        $DeviceObjectAsArray=$Device->toArray();
        $DevicetypeID=$Device->devicetype_fid;
        $DevicetypeObject=$DevicetypeID>0?sas_devicetype::find($DevicetypeID):'';
        $DeviceObjectAsArray['devicetypeinfo']=$DevicetypeObject->toArray();
        $OwnerunitID=$Device->owner__unit_fid;
        $OwnerunitObject=$OwnerunitID>0?sas_unit::find($OwnerunitID):'';
        $DeviceObjectAsArray['ownerunitinfo']=$OwnerunitObject->toArray();

        $requestsOBJ=$Device->requests();
        $DeviceObjectAsArray['requests']=$this->getNormalizedList($requestsOBJ->toArray());

        $Device = $this->getNormalizedItem($DeviceObjectAsArray);
        return response()->json(['Data'=>$Device], 200);
    }
    public function delete($id,Request $request)
    {
        if(!Bouncer::can('sas.device.delete'))
            throw new AccessDeniedHttpException();
        $Device = sas_device::find($id);
        $Device->delete();
        return response()->json(['message'=>'deleted','Data'=>[]], 202);
    }
}