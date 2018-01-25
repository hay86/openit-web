@extends('layouts.app')

@section('stylesheets')
    <link href="http://cdn.bootcss.com/select2/4.0.3/css/select2.min.css" rel="stylesheet">
    <link href="http://cdn.bootcss.com/wangeditor/2.1.20/css/wangEditor.min.css" rel="stylesheet">
@endsection

@section('title', '编辑文章')

@section('content')

    <div class="container">
        <div class="row">
            <div class="col-md-12 text-center">
                <h1><small>编辑文章</small></h1>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <form class="form-horizontal" role="form" method="POST" action="{{ route('admin.articles.update', $article->id) }}">
                    {{ csrf_field() }}
                    {{ method_field('PUT') }}
                    <div class="form-group">
                        <label for="title" class="col-sm-1 control-label">标题</label>
                        <div class="col-sm-11">
                            <input type="text" class="form-control" id="title" name="title" value="{{ !empty(old('title')) ? old('title') : $article->title }}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="subtitle" class="col-sm-1 control-label">摘要</label>
                        <div class="col-sm-11">
                            <input type="text" class="form-control" id="subtitle" name="subtitle" value="{{ !empty(old('subtitle')) ? old('subtitle') : $article->subtitle }}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="image" class="col-sm-1 control-label">封面图</label>
                        <div class="col-sm-11">
                            <input type="image" class="img-thumbnail" id="image" src="{{ !empty(old('image_url')) ? old('image_url') : $article->img_lg() }}" width="240">
                            <input type="file" class="hidden" id="image_file">
                            <input type="hidden" id="image_url" name="image_url" value="{{ !empty(old('image_url')) ? old('image_url') : $article->img_lg() }}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="label" class="col-sm-1 control-label">标签</label>
                        <div class="col-sm-11">
                            <select class="form-control" multiple="multiple" id="tags" name="tag_ids[]">
                                @foreach ($tags as $tag)
                                    <option value="{{ $tag->id }}" {{ !empty(old('tag_ids')) &&  in_array($tag->id, old('tag_ids')) || in_array($tag->id, $article->tags->pluck('id')->toArray()) ? 'selected' : '' }}>{{ $tag->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="body" class="col-sm-1 control-label">正文</label>
                        <div class="col-sm-11">
                            <textarea name="body" id="body" class="form-control" rows="25">{{ !empty(old('body')) ? old('body') : $article->body }}</textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-1 col-sm-11">
                            <button type="submit" class="btn btn-primary">保存</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script>
        $('#image').click(function(){
            $('#image_file').click();
            return false;
        });
        $('#image_file').change(function(){
            var file = this.files[0];
            if (file.size > 2097152) {
                alert('上传图片需小于2MB！');
            }
            else {
                var data = new FormData();
                data.append('image_file', file);
                $.ajax({
                    url             : "{{ route('admin.images.store') }}",
                    headers         : { 'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content') },
                    type            : "POST",
                    data            : data,
                    cache           : false,
                    contentType     : false,
                    processData     : false,
                    success         : function( url ){
                        $('#image').attr('src', url);
                        $('#image_url').val(url);
                        $('#image_file').val("");
                    },
                    error           : function(){
                        alert('上传失败，请重试！');
                    }
                });
            }
        });
    </script>
    <script src="http://cdn.bootcss.com/select2/4.0.3/js/select2.min.js"></script>
    <script>$('#tags').select2();</script>
    <script src="http://cdn.bootcss.com/wangeditor/2.1.20/js/wangEditor.min.js"></script>
    <script>
        var editor = new wangEditor($('#body'));
        editor.config.uploadImgUrl = '{{ route('admin.images.store') }}';
        editor.config.uploadParams = {_token : '{{ csrf_token() }}'};
        editor.config.uploadImgFileName = 'image_file'
        editor.config.menus = [
            'source',
            '|',
            'bold',
            'underline',
            'italic',
            'strikethrough',
            'eraser',
            'forecolor',
            'bgcolor',
            '|',
            'quote',
            'fontfamily',
            'fontsize',
            'head',
            'unorderlist',
            'orderlist',
            'alignleft',
            'aligncenter',
            'alignright',
            'lineheight',
            'indent',
            '|',
            'link',
            'unlink',
            'table',
            'emotion',
            'img',
            '|',
            'undo',
            'redo',
            'fullscreen'
        ];
        editor.config.emotions = {
            'default': {
                title: '默认',
                data: '{{ url("data/emotions.data") }}'
            }
        };
        editor.create();
    </script>
@endsection