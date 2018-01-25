<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    public $incrementing = false;

    const ORPHAN_ID = NULL;

    public function article() {
        return $this->belongsTo('App\Article');
    }

    private static function gen_url($id, $style) {
        return 'http://' . config('oss.img_domain') . '/' . $id . '/' . $style;
    }

    public static function gen_xs_url($id) {
        return self::gen_url($id, '240');
    }

    public static function gen_sm_url($id) {
        return self::gen_url($id, '480');
    }

    public static function gen_md_url($id) {
        return self::gen_url($id, '960');
    }

    public static function gen_lg_url($id) {
        return self::gen_url($id, '1920');
    }

    public static function gen_xs_wide_url($id) {
        return self::gen_url($id, '240x135');
    }

    public static function gen_sm_wide_url($id) {
        return self::gen_url($id, '480x270');
    }

    public static function gen_md_wide_url($id) {
        return self::gen_url($id, '960x540');
    }

    public static function gen_lg_wide_url($id) {
        return self::gen_url($id, '1920x1080');
    }

    public static function gen_xs_sqr_url($id) {
        return self::gen_url($id, '240x240');
    }

    public static function gen_sm_sqr_url($id) {
        return self::gen_url($id, '480x480');
    }

    public static function gen_md_sqr_url($id) {
        return self::gen_url($id, '960x960');
    }

    public static function gen_lg_sqr_url($id) {
        return self::gen_url($id, '1920x1920');
    }

    private static $allowed_ext = ['jpg', 'jpeg', 'png', 'webp', 'bmp', 'gif'];

    public static function gen_id($ext) {
        if (!in_array($ext, self::$allowed_ext)) {
            return null;
        }
        $timestamp = floor(microtime(true) * 1000);
        return base_convert($timestamp, 10, 36) . '.' . $ext;
    }

    public static function get_id($url) {
        $path = parse_url($url, PHP_URL_PATH);
        $pattern = '/[a-z0-9]{8}\.(' . implode('|', self::$allowed_ext) . ')/';

        if (preg_match($pattern, $path, $matches)) {
            return $matches[0];
        }
        return null;
    }

    public static function get_ids($body) {
        $image_ids = [];

        $dom = new \DOMDocument();
        $html = preg_replace('/&(?!#?[a-z0-9]+;)/', '&amp;', $body);
        $dom->loadHTML($html);

        $images = $dom->getElementsByTagName('img');
        foreach ($images as $img) {
            $url = $img->getAttribute('src');
            $id = self::get_id($url);
            if (!empty($id))
                $image_ids[] = $id;
        }

        return $image_ids;
    }
}
