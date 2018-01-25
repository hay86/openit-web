<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Image;
use OSS\OssClient;
use OSS\Core\OssException;

class ImageController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    public function store (Request $request) {
        $this->validate($request, [
            'image_file'    => 'required|image',
        ]);

        // read oss config
        $access_id      = config('oss.access_key_id');
        $access_secret  = config('oss.access_key_secret');
        $endpoint       = config('oss.endpoint');
        $timeout        = config('oss.timeout');
        $conn_timeout   = config('oss.conn_timeout');
        $bucket         = config('oss.img_bucket');

        // init oss
        try {
            $oss = new OssClient($access_id, $access_secret, $endpoint);
            $oss->setTimeout($timeout);
            $oss->setConnectTimeout($conn_timeout);
            if (!$oss->doesBucketExist($bucket)) {
                $oss->createBucket($bucket);
            }
        } catch (OssException $e) {
            abort(500, $e->getMessage());
        }

        $file = $request->file('image_file');
        $filename = Image::gen_id($file->extension());

        if (empty($filename)) {
            $msg = 'Failed to generate image id with .' . $file->extension();
            abort(500, $msg);
        }

        // upload image
        try {
            $options = [OssClient::OSS_HEADERS => [
                'Content-Type' => $file->getMimeType(),
            ]];
            $oss->uploadFile($bucket, $filename, $file->path(), $options);
        } catch (OssException $e) {
            abort(500, $e->getMessage());
        }

        // save db
        $image = new Image;
        $image->id = $filename;
        $image->article_id = Image::ORPHAN_ID;
        $image->save();

        return Image::gen_md_url($filename);
    }
}
