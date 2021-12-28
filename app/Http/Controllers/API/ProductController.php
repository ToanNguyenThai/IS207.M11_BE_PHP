<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Model\Image;
use App\Models\Model\Product;
use App\Models\Model\ProductDetail;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    // function lấy về tất cả sản phẩm
    public function getAll(){
        $product = Product::select('id','name as product_name','price as product_price','imgURL','type as product_type','total_product as totalProducts')
        ->get(); 
        foreach($product as $item){
            $product_detail = ProductDetail::where('product_id',$item->id)->get();
            $size = array(); 
            $color = array();
            if(count($product_detail)>=1){
                $size = array();
                $color = array();
                foreach($product_detail as $detail){
                    array_push($size,$detail->size);
                    array_push($color,$detail->color);
                }
            }
            $item->product_colors = $color;
            $item->product_sizes = $size;

            $image = Image::where('product_id',$item->id)->get();
            if($image->count()>=1){
                $img = array();
                foreach($image as $item2){
                    array_push($img,$item2->image);
                }
                $item->product_images = $img;
            }
        }

        return response(["products"=>$product],200);
        
    }
    
    public function getdetail($id){
        $product= Product::select('id','name as product_name','price as product_price','imgURL','type as product_type','total_product as totalProducts')
        ->where('id',$id)
        ->first();
        if($product == null){
            return response('sản phẩm không tồn tại',404);
        }
        
        $image = Image::where('product_id',$product->id)->get(); 
        
        if($image->count()>=1){
            
            $img = array();
            foreach($image as $item2){
                array_push($img,$item2->image);
            }
            $product->product_images = $img;
        }

         $product_detail = ProductDetail::where('product_id',$id)->get();
            $size = array();
            $color = array();
            if(count($product_detail)>=1){
                $size = array();
                $color = array();
                // $number = array();
                foreach($product_detail as $detail){
                    array_push($size,$detail->size);
                    array_push($color,$detail->color);
                }
            }
        $product->product_sizes = $size;
        $product->product_colors = $color;
        // $product->size = $detail->size;
        //trả về 200
        return response($product,200);
    }
    public function insert(Request $request){
        //khởi tạo sản phẩm
        $product = new Product();
        $product->name= $request->name; //gán tên sản phẩm trong db
        $product->price = $request->price; //giá sản phẩm
        $product->type = $request->purpose; //kiểu sản phẩm
        $product->total_product = 0; // số lượng mặc định
        $product->imgURL = $request->imgSrc;
        //xử lí ảnh chính
        $product->save(); //lưu sản phẩm
        //Xử lí ảnh chi tiết
        $image =json_decode($request->details_sideImg) ;
//        return response(["thêm thành công"=>$image],200); // nếu thành công sẽ trả về status code 200

//chạy vòng for
        foreach($image as $key=>$img){
                $imgdetail = new Image();
                $imgdetail->product_id = $product->id;
                $imgdetail->image = $img;
                $imgdetail->save(); // lưu db
            }
        return response("thêm thành công",200); // nếu thành công sẽ trả về status code 200
    }
    //function thêm sản phẩm
//    public function insert(Request $request){
//        //khởi tạo sản phẩm
//        $product = new Product();
//        $product->name= $request->name; //tên sản phẩm
//        $product->price = $request->price; //giá sản phẩm
//        $product->type = $request->purpose; //kiểu sản phẩm
//        $product->total_product = 0; // số lượng mặc định
//        //xử lí ảnh chính
//        if($request->hasFile('imgSrc')){
//            $imageURL =time().'.'.$request->imgSrc->getClientOriginalExtension();
//            $request->imgSrc->move(public_path('/upload/product'),$imageURL); // lưu ảnh
//            $product->imgURL = $imageURL;
//        }
//        $product->save(); //lưu sản phẩm
//        //Xử lí ảnh chi tiết
//        if($request->hasFile('details_sideImg')){
//            // $test= array();
//            foreach($request->details_sideImg as $key=>$img){
//                $imgdetail = new Image();
//                $imgdetail->product_id = $product->id;
//                $image1 =time().$key.'.'.$img->getClientOriginalExtension();
//                $img->move(public_path('upload/product_detail'),$image1);
//                $imgdetail->image = $image1;
//                $imgdetail->save(); // lưu ảnh
//            }
//        }
//         return response("thêm thành công",200); // nếu thành công sẽ trả về status code 200
//    }

//    public function update(Request $request){
//        $product = Product::find($request->id);
//        if ($product == null){
//            return response("Sản phẩm không tồn tại",404);
//        }
//        else {
//            $product->name= $request->name; //tên sản phẩm
//            $product->price = $request->price; //giá sản phẩm
//            $product->type = $request->purpose; //kiểu sản phẩm
//            if($request->hasFile('imgSrc')){
//                $imageURL =time().'.'.$request->imgSrc->getClientOriginalExtension();
//                $request->imgSrc->move(public_path('/upload/product'),$imageURL);
//                unlink(public_path('/upload/product/').$product->imgURL); // xóa ảnh cũ
//                $product->imgURL = $imageURL;
//            }
//            if($request->hasFile('details_sideImg')){
//                $imageDetail = Image::where('product_id',$request->id)->get(); //tìm mảng ảnh cũ
//                $imageDetail;
//                if(count($imageDetail) >= 1 )
//                {
//                    foreach($imageDetail as $imgremove){
//                        unlink(public_path('/upload/product_detail/').$imgremove->image);
//                        $imgremove->delete();
//                    }
//                }
//                foreach($request->details_sideImg as $key=>$img){
//                    $imgdetail = new Image();
//                    $imgdetail->product_id = $product->id;
//                    $image1 =time().$key.'.'.$img->getClientOriginalExtension();
//                    $img->move(public_path('upload/product_detail'),$image1);
//                    $imgdetail->image = $image1;
//                    $imgdetail->save(); // lưu ảnh
//                }
//            }
//            $product->save();
//            return response("OK",200);
//        }
//    }
    public function update(Request $request){
        //tìm ra thằng sản phẩm để mà cập nhật
        $product = Product::find($request->id);
        if ($product == null){
            return response("Sản phẩm không tồn tại",404);
        }
        else {
            $product->name= $request->name; //tên sản phẩm
            $product->price = $request->price; //giá sản phẩm
            $product->type = $request->purpose; //kiểu sản phẩm
                $imageDetail = Image::where('product_id',$request->id)->get(); //tìm mảng ảnh cũ
                $imageDetail;
                //đếm
                if(count($imageDetail) >= 1 )
                {
                    foreach($imageDetail as $imgremove){
                        $imgremove->delete();//xóa
                    }
                }
            $image =json_decode($request->details_sideImg) ;
                foreach( $image as $key=>$img){
                    $imgdetail = new Image();
                    $imgdetail->product_id = $product->id;
                    $imgdetail->image = $img;
                    $imgdetail->save(); // lưu ảnh
                }
            $product->save();//lưu sản phẩm
            return response("OK",200);//trả về OK 200
        }
    }
    public function insert_detail(Request $request){
        $detail1 = ProductDetail::where('product_id',$request->product_id)
        //có tồn tại màu, size này ko
        ->where('color',$request->color)
        ->where('size',$request->size)->first();

        if($detail1!=null){
            $detail1->quantity+= $request->number;
            $detail1->save();
            $product = Product::find($request->product_id);
            $soluong = ProductDetail::where('product_id',$request->product_id)
            ->sum('quantity');//sl
            $product->total_product = $soluong;//lưu sl lưu lại
            $product->save();
            return  response("thêm số lượng thành công",200);
        } 
            $detail = new ProductDetail();
            $detail->product_id = $request->product_id;//thêm product_id
            $detail->color = $request->color;//thêm color vô color
            $detail->size = $request->size;
            $detail->quantity = $request->number;
            $detail->save();// lưu chi tiết
            
            $product = Product::find($request->product_id);
                $soluong = ProductDetail::where('product_id',$request->product_id)
                ->sum('quantity');
                $product->total_product = $soluong;
                $product->save();//lưu product lại
            return response("thêm thành công",200);
    }
    public function update_detail(Request $request){
        $detail = ProductDetail::find($request->id);

        if ($detail==null){
            return response("không tồn tại",404);
        }
        $product_id = $detail->product_id;
            $detail->quantity = $request->number;
            $detail->save();
        $product = Product::find($product_id);
        $soluong = ProductDetail::where('product_id',$product_id)
        ->sum('quantity');
        $product->total_product = $soluong;
        $product->save();
        return response("oke",200);
    }
    public function delete(Request $request){
        $product = Product::find($request->id);
        if($product==null){
            return response("khong tim thay",404);
        }
        $product_detail = ProductDetail::where('product_id',$product->id)->get();
        if(count($product_detail)>=1){
            foreach($product_detail as $item ){
                $item->delete();
            }
        }
        $image = Image::where('product_id',$product->id)->get();
        foreach($image as $item ){
     $item->delete();
        }
        $product->delete();
        return response("xoa ok",200);
    }

}
