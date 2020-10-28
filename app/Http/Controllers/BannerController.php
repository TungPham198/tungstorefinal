<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\banner;

class BannerController extends Controller
{
    //
    public function getDanhSach(){
    	$banner = Banner::all();
    	return view('admin.banner.danhsachbanner',['banner'=>$banner]);
    }

    public function getThem(){
    	return view('admin.banner.thembanner');
    }
    public function postThem(Request $request){
    	$this->validate($request,
    		[
    			'ten'=>'required||min:3|max:100',
    			'loai'=>'required',
    			
    		],
    		[
    			'ten.required'=>'Bạn chưa nhập tên',
            
                'ten.min'=>'Tên phải lớn hơn 3 kí tự',
                'ten.max'=>'Tên phải nhỏ hơn 100 kí tự',
                'loai.required'=>'Bạn chưa chọn loại',
                
    		]);
    	$bn = new Banner();
    	if($request->hasFile('anh')){
    		$file = $request->file('anh');
    		$duoi = $file->getClientOriginalExtension();
    		if($duoi != 'jpg' && $duoi !='png' && $duoi !='jpeg'){
    			return back()->with('loianh','Bạn chỉ được chọn file đuôi jpg, png, jpeg');
    		}
    		$name = $file->getClientOriginalName();
    		$hinh = str::random(4)."_".$name;
    		while(file_exists("upload/banner/".$hinh)){
    			$hinh = str::random(4)."_".$name;
    		}
    		$file->move("upload/banner",$hinh);
    		$bn->anh = $hinh;
    	}else{
    		$bn->anh = "";
    	}
    	$bn->ten_banner = trim($request->ten);
    	$bn->loai = $request->loai;
    	$bn->save();
    	return redirect()->route('getAllBanner');
    }

    public function getSua($id){
    	$banner = Banner::find($id);
    	return view('admin.banner.suabanner',['banner'=>$banner]);
    }
    public function postSua($id,Request $request){
    	$this->validate($request,
    		[
    			'ten'=>'required|min:3|max:100',
    			'loai'=>'required',
    		],
    		[
    			'ten.required'=>'Bạn chưa nhập tên',
                'ten.min'=>'Tên phải lớn hơn 3 kí tự',
                'ten.max'=>'Tên phải nhỏ hơn 100 kí tự',
                'loai.required'=>'Bạn chưa chọn loại',
    		]);
    	$bn = Banner::find($id);
    	if($request->hasFile('anh')){
    		$file = $request->file('anh');
    		$duoi = $file->getClientOriginalExtension();
    		if($duoi != 'jpg' && $duoi !='png' && $duoi !='jpeg'){
    			return back()->with('loianh','Bạn chỉ được chọn file đuôi jpg, png, jpeg');
    		}
    		$name = $file->getClientOriginalName();
    		$hinh = str::random(4)."_".$name;
    		while(file_exists("upload/banner/".$hinh)){
    			$hinh = str::random(4)."_".$name;
    		}
    		$file->move("upload/banner",$hinh);
    		$bn->anh = $hinh;
    	}else{
    		$bn->anh = "";
    	}
    	$bn->ten_banner = trim($request->ten);
    	$bn->loai = $request->loai;
    	$bn->save();
    	return redirect()->route('getAllBanner');
    }

    public function getXoa($id){
    	$bn = Banner::find($id);
    	if(file_exists('upload/sanpham/'.$bn->ten_banner)){
    		unlink('upload/sanpham/'.$bn->ten_banner);
    	}
        $bn->delete();
        return back();
    }
}
