<?php
# idxCMS Flat Files Content Management Sysytem
# Editor
# Version 2.4
# Copyright (c) 2011 - 2015 Victor Nabatov

if (!defined('idxCMS') || !USER::$logged_in) die();

if (!empty($REQUEST['image']['name'])) {
    try {
        $IMAGE = new IMAGE(TEMP);
        $uploaded = $IMAGE->upload($REQUEST['image']);
        $IMAGE->generateThumbnail();
        $dir = TEMP;
    } catch (Exception $error) {
        echo __($error->getMessage());
    }
}
?>
<!DOCTYPE html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta http-equiv="Content-Language" content="<?php echo SYSTEM::get('locale');?>" />
    <?php if (empty($REQUEST['text'])) { ?>
            <title>idxCMS version <?php echo IDX_VERSION.' - '.__('Images browser');?></title>
            <style type="text/css">
                <!--
                body     { background:#4e889d; color:black; font:normal 10px arial,verdana,sans-serif; margin:0; padding:0 }
                #caption { font-size:10px; height:40px; margin:0; padding:0 }
                #close   { width:100%; margin:5px; text-align:center; position:absolute; bottom:0 }
                #files   { background:#e2e2e2; width:30%; overflow:auto }
                #picture { background:#f0f0f0; width:70%; overflow:auto; min-height:390px; float:left }
                #preview { width:100%; min-height:320px }
                #upload  { background:#d2d2d2; color:black; margin:0 0 10px; padding:0 10px; border:0 }
                #window  { padding:10px }
                -->
            </style>
            <script type="text/javascript">
                function _wImage(url) {
                    window.opener.document.getElementById('image_url_' + window.opener.id).value = url;
                    window.close();
                }
            </script>
            </head>
            <body>
            <div id="window">
                <div>
                    <form id="upload" method="post" action="" enctype="multipart/form-data">
                        <label for="image"><?php echo __('File');?>: </label>
                        <input type="file" id="image" name="image" size="50" />
                        <input type="submit" value="<?php echo __('Upload');?>" />
                    </form>
                </div>
                <div id="picture">
                    <fieldset><legend><?php echo __('Preview')?></legend>
                        <iframe id="preview" src="javascript:document.write('<?php echo __('Preview');?>');"></iframe>
                        <div id="caption"><?php echo __('Info');?></div>
                    </fieldset>
                </div>
                <div id="files">
                    <fieldset><legend><?php echo __('Files');?></legend>
                        <?php
                        $dir = empty($dir) ? CONTENT.'images'.DS : TEMP;
                        $files = GetFilesList($dir);
                        $list  = '';
                        $image = '';
                        foreach ($files as $file) {
                            if (!preg_match("/\.(gif|jpg|jpeg|png).jpg$/", $file)) {
                                if (preg_match("/\.(gif|jpg|jpeg|png)$/", $file)) {
                                    $image = $dir.$file;
                                    list($width, $height, $type, $attr) = getimagesize($image);
                                    $tip   = __('Size').': '.$width.'x'.$height.' px = '.sprintf("%u", filesize($image)).' '.__('byte(s)').'<br />'.__('Date').': '.date("Y-m-d H:i:s", filemtime($image));
                                    $list .= '<b>&bull;</b> <a href="#" onclick="_wImage(\''.$file.'\');" onmouseover="document.getElementById(\'preview\').src=\''.$image.'\';document.getElementById(\'caption\').innerHTML=\'<b>'.$file.'</b><br />'.$tip.'\'">'.$file.'</a><br />'.LF;
                                }
                            }
                        }
                        echo $list;?>
                    </fieldset>
                </div>
            </div>
            <div id="close"><form method="post" action=""><input type="button" onclick="window.close();" value="<?php echo __('Close');?>" /></form></div>
<?php } else {
          if (!empty($REQUEST['text'])) {
            $title = empty($REQUEST['title']) ? '' : $REQUEST['title'];
            $text = preg_replace('/<br[^>]*?>/si', LF, $REQUEST['text']);
            $text = CMS::call('PARSER')->parseText($text);?>
            <title>idxCMS version <?php echo IDX_VERSION.' - '.__('Article preview');?></title>
            <style type="text/css">
                <!--
                body      { background:#4e889d; color:black; font:normal 0.8em arial,verdana,sans-serif; margin:0; padding:0 }
                h1        { font-size:1.2em; font-weight:normal; line-height:22px; margin:0 0 10px }
                #close    { width:100%; margin:5px; text-align:center; position:relative; bottom:0 }
                #preview  { background:white; width:100%; min-height:500px }
                #window   { padding:10px }
                #text img { float:left }
                .title    { font-size:1.2em; font-weight:bold; margin:5px 0; padding:5px; text-align:center }
                .codehtml { margin:0 0 1px; padding:5px; border:#6da4da solid 1px; background:#ffffc0; color:black; font:normal 9pt mono,"Courier New",Courier; overflow:auto; white-space:nowrap }
                .codephp  { margin:0 0 1px; padding:5px; border:#6da4da solid 1px; background:#ffffc0; color:black; font:normal 9pt mono,"Courier New",Courier; overflow:auto; white-space:nowrap }
                .codetext { margin:0 0 1px; padding:5px; border:#6da4da solid 1px; background:#ffffc0; color:black; font:normal 9pt mono,"Courier New",Courier; overflow:auto; white-space:nowrap }
                -->
            </style>
            </head>
            <body>
            <div id="window">
                <div id="preview">
                    <fieldset><legend><?php echo __('Preview');?></legend>
                        <div class="title"><h1><?php echo $title;?></h1></div>
                        <div id="text" class="justify"><?php echo $text;?></div>
                    </fieldset>
                </div>
            </div>
            <div id="close"><form method="post" action=""><input type="button" onclick="window.close();" value="<?php echo __('Close');?>" /></form></div>
    <?php }
} ?>
</body>
</html>
