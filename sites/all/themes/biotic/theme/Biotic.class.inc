<?php

require_once dirname(__FILE__) . '/includes/scss.inc.php';

// scss_formatter
// scss_formatter_nested (default)
// scss_formatter_compressed
if (variable_get('preprocess_css', false)) {
define('SCSS_FORMATTER',"scss_formatter_compressed");
}else {
define('SCSS_FORMATTER',"scss_formatter");
}
define('SCSS_PATH', dirname(__FILE__) . '/includes/scss');


/**
*
*/
class Biotic
{
     var $theme;
     var $page;
     var $layouts;
     var $regions = array();
     var $block   = array();
     var $media   = array();
     var $typo    = array();

    function __construct($theme = NULL)
    {
        if ($theme == NULL) {
          $theme = $GLOBALS['theme_key'];
        }
        $this->theme = $theme;
        $this->init();
    }

    function init()
    {

      // LOAD THEME DRAG AND DROP LAYOUT SETTINGS
      $this->layouts = json_decode( urldecode( base64_decode(  theme_get_setting('layouts',$this->theme) ) ) );

      // LOAD TYPOGRAPHY SETTINGS
      $this->typo = json_decode( urldecode( base64_decode( theme_get_setting('typo', $this->theme) ) ) );

      if (!$this->layouts) {
        // LOAD DEFAULT SETTINGS
        $this->layouts = json_decode( urldecode( base64_decode( $this->default_settings() ) ) );
      }

      // MERGE ALL REGIONS
      foreach ($this->layouts as $section) {
        foreach ($section->regions as $key => $region) {
          $this->regions[$region->key] = (array)$region;
        }
      }

      // MERGE ALL Medias
      foreach ($this->layouts as $section) {
        if ( isset($section->background_media) && $section->background_media > 0 ) {
        // if ( isset($section->background_media) && !empty($section->background_media)  ) {
        //
        //   if ( is_numeric($section->background_media) ) {
        //
        //     $fid = $section->background_media ;
        //
        //   } else {
        //     $media_obj = json_decode( $section->background_media);
        //     $fid = $media_obj->fid;
        //
        //   }

          $fid = $section->background_media ;
          $file = (array)file_load($fid);
          if ( isset($file[0]) && !$file[0] ) {

             $object_msg = $section ? 'Object: ' . var_export($section, TRUE) : '';

            $message = t("Missing Background Image in the <strong>%s</strong> Section. Please make sure the file is exist in your system.",array('%s' => $section->section_name ));
            drupal_set_message( $message,'warning');
            watchdog('Theme', $message. '<pre>'. $object_msg .'</pre>' );

          } else {

            $image = image_load($file['uri']);
            $url = image_style_url('media_thumbnail', $file['uri'] );
            $file['url'] = $url;

            $this->media[$fid] = $file;

          }

        }
      }

      // dpm($this->layouts,"Layouts");
      // dpm($this->regions,"regions");
      // dpm($this->media,"Medias");
    }

    public function decodeData($data) {

      return json_decode( urldecode( base64_decode($data) ) );
    }

    public function default_settings()
    {
      # code...
      include_once drupal_get_path("theme", $this->theme) . "/default-settings.php";
      $default = $this->theme . '_default_settings';

      if( function_exists($default) ){
        return $default();
      }

      return false;

    }


    private function put_quotes ($name) {
        $name = trim($name);
        return strpos($name, ' ') ? "'" . $name . "'" : $name;
    }


    /**
     * [load_typo_settings description]
     * @return [type] [description]
     */
    public function typo_css()
    {

      $scss = new scssc();
      // $scss->setImportPaths(dirname(__FILE__));
      $scss->setImportPaths(SCSS_PATH);
      $scss->setFormatter(SCSS_FORMATTER);


      $TypoCSS = "";

      // (Object) stdClass
      // selector (String )
      // backup_font_family (String )
      // font_family (String )
      // font_size (Integer)
      // font_subset (Array)
      // - 0 (Strin )
      // font_variants (Array)
      // - 0 (String)
      // font_style: (String)
      // font_variant: (String)
      // font_weight: (String)
      //
      // letter_spacing (String )
      // line_height (String )
      // text_align (String) left
      // word_spacing (String)

      // text_decoration: (String)
      // text_shadow: (String)
      // text_transform: (String)

      // font_size_type: {$font_size_type}
      // line_height_type line_height_type}
      // word_spacing_type word_spacing_type}
      // letter_spacing_type {$letter_spacing_type}

      $selector = array();
      $font     = "false";
      $fonts    = array();
      $subsets  = array();
      $CSS_Typo = "";

      // $attr = array(
      //   'selector',
      //   'backup_font_family',
      //   'font_family',
      //   'font_subset',
      //   'font_weight',

      //   'font_size',
      //   'letter_spacing',
      //   'line_height',
      //   'text_align',
      //   'word_spacing',
      // );

      if ($this->typo) {

        foreach ($this->typo as $key => $value) {

          $selector_type = "false";

          if ( $value->selector[0] == "." ) {
            // $value->selector = ltrim($value->selector, '.');
            $selector_type = "class";
          }
          if ( $value->selector[0] == "#" ) {
            // $value->selector = ltrim($value->selector, '#');
            $selector_type = "id";
          }

          $font_family        = implode(', ', array_map(array($this, 'put_quotes'), explode(',', $value->font_family )));
          $backup_font_family = implode(', ', array_map(array($this, 'put_quotes'), explode(',', $value->backup_font_family )));
          // $font = "false";

          if ($font_family) {
            $font = $font_family;

            if ($backup_font_family) {
              $font = $font_family. ",".$backup_font_family;
            }

          } elseif ($backup_font_family) {
            $font = $backup_font_family;
          } else {
            $font = 'false';
          }

          // selector
          // selector_type
          // Font
          $font_size           = ($value->font_size) ? $value->font_size : "false";
          $font_style          = ($value->font_style) ? $value->font_style : "false";
          $font_variant        = ($value->font_variant) ? $value->font_variant : "false";
          $font_weight         = ($value->font_weight) ? $value->font_weight : "false";

          $text_decoration     = ($value->text_decoration) ? $value->text_decoration : "false";
          $text_shadow         = ($value->text_shadow) ? $value->text_shadow : "false";
          $text_transform      = ($value->text_transform) ? $value->text_transform : "false";

          $letter_spacing      = ($value->letter_spacing) ? $value->letter_spacing : "false";
          $line_height         = ($value->line_height) ? $value->line_height : "false";
          $text_align          = ($value->text_align) ? $value->text_align  : "false";
          $word_spacing        = ($value->word_spacing) ? $value->word_spacing : "false";

          $font_size_type      = ($value->font_size_type) ? $value->font_size_type : "false";
          $line_height_type    = ($value->line_height_type) ? $value->line_height_type : "false";
          $word_spacing_type   = ($value->word_spacing_type) ? $value->word_spacing_type : "false";
          $letter_spacing_type = ($value->letter_spacing_type) ? $value->letter_spacing_type : "false";


$DRV = <<< TL
\$selector: "{$value->selector}";
\$selector_type: {$selector_type};
\$font: "{$font}";

\$font_size: {$font_size};
\$font_style: {$font_style};
\$font_variant: {$font_variant};
\$font_weight: {$font_weight};

\$text_decoration: {$text_decoration};
\$text_shadow: {$text_shadow};
\$text_transform: {$text_transform};

\$letter_spacing: {$letter_spacing};
\$line_height: {$line_height};
\$text_align: {$text_align};
\$word_spacing: {$word_spacing};

\$font_size_type: {$font_size_type};
\$line_height_type: {$line_height_type};
\$word_spacing_type: {$word_spacing_type};
\$letter_spacing_type: {$letter_spacing_type};

@import "typo";
TL;


          $TypoCSS .= $scss->compile($DRV)."\n";

        }

      }

      return $TypoCSS;
    }

    /**
     *
     * [_google_web_fonts description]
     *
     * @return [array] [All google webfonts]
     */
    public function get_web_fonts() {
      // array_unique(array)

      $typo = $this->typo;

      $Webfonts = array();

      if ($typo) {

        foreach ($typo as $key => $value) {

          if ( isset($Webfonts[$value->font_family]) ) {

          $Webfonts[$value->font_family]['subsets'] = array_unique(
            array_merge(
              $Webfonts[$value->font_family]['subsets'],
              $value->font_subset
            )
          );

          $Webfonts[$value->font_family]['variants'] = array_unique(
            array_merge(
              $Webfonts[$value->font_family]['variants'],
              $value->font_variants
            )
          );


          }else {
            if ($value->font_family) {
              $Webfonts[$value->font_family] = array(
                'subsets' => $value->font_subset,
                'variants' => $value->font_variants
              );
            }
          }

        }

      }


      return (array)$Webfonts;
    }

    public function _webfont_family()
    {

      $fonts_array = $this->get_web_fonts();
      $font = array();

      foreach ($fonts_array as $key => $value) {

        // Reset the Fonts
        $f = array();

        if (!$key) {
          // // skip Empty Fonts family
           continue;
        }


        $patterns = array();
        $replacements = array();

        $patterns[0] = '/\b(regular)\b/i';
        $patterns[1]  = '/\b(italic)\b/i';

        $replacements[0] = '400';
        $replacements[1] = '400italic';


        $value['variants'] = preg_replace($patterns, $replacements , $value['variants']);

        asort($value['variants']);

        $f[] =  str_replace(" ", "+", $key);
        $f[] =  ($value['variants']) ? implode(",", $value['variants'])  : "" ;
        $f[] =  ($value['subsets']) ? implode(",", $value['subsets']) : "latin";

        $font[] = implode(":", $f);

      }

      // debug($font, "Font");

      return $font;

    }

    public function compressed_css_file_create($CSS)
    {
      $prefix = "css";
      $hash = user_password(8);
      // $hash = "WfzUwtTs";
      $file = "public://".$prefix."-".$hash.".css";

      if (file_unmanaged_save_data($CSS,$file,FILE_EXISTS_ERROR)) {
        return $file;
      }else{
        return false;
      }

    }

    public function get_section_css(){
      $css = "";
      foreach ($this->layouts as $key => $value) {
        # code...
        // debug($value);
        $css .= $this->sectionCSS((array)$value);
      }

      $css = $css ? $css : false;

      // debug("get section css");
      // debug($css, "CC");

      return $css;
    }

    /**
     * Calculate Main content Column
     * @return [array] [ Concent Column ]
     */
    function calc_column()
    {
      $cols = array(
        'xs' => 0,
        'sm' => 0,
        'md' => 0,
        'lg' => 0
      );

      // dpm($this->page);

      // Sidebar First
      if (!isset($this->page['page']['data']['main']['sidebar_first']) ) {
        $cols['xs'] = $this->regions['sidebar_first']['colxs'];
        $cols['sm'] = $this->regions['sidebar_first']['colsm'];
        $cols['md'] = $this->regions['sidebar_first']['colmd'];
        $cols['lg'] = $this->regions['sidebar_first']['collg'];
      }

      // Sidebar Second
      if (!isset($this->page['page']['data']['main']['sidebar_second']) ) {
        $cols['xs'] += $this->regions['sidebar_second']['colxs'];
        $cols['sm'] += $this->regions['sidebar_second']['colsm'];
        $cols['md'] += $this->regions['sidebar_second']['colmd'];
        $cols['lg'] += $this->regions['sidebar_second']['collg'];
      }

      // Content Column
      if (isset($this->page['page']['data']['main']['content'])) {
        $cols['xs'] += $this->regions['content']['colxs'];
        $cols['sm'] += $this->regions['content']['colsm'];
        $cols['md'] += $this->regions['content']['colmd'];
        $cols['lg'] += $this->regions['content']['collg'];
      }

      // Fix Column Calculation
      $cols['xs'] = ( $cols['xs'] > 12 ) ? 12 : $cols['xs'];
      $cols['sm'] = ( $cols['sm'] > 12 ) ? 12 : $cols['sm'];
      $cols['md'] = ( $cols['md'] > 12 ) ? 12 : $cols['md'];
      $cols['lg'] = ( $cols['lg'] > 12 ) ? 12 : $cols['lg'];

      // dvm($cols,"Cols");

      return $cols;

    }

  //
  // Prepared from the http://www.mendoweb.be/blog/php-convert-string-to-camelcase-string
  //
  public static function camelCase( $str,$exclude = array(),$first_Upper = TRUE )
  {
    // replace accents by equivalent non-accents
    $str = self::replaceAccents($str);
    // non-alpha and non-numeric characters become spaces
    $str = preg_replace('/[^a-z0-9' . implode("", $exclude) . ']+/i', ' ', $str);
    // uppercase the first character of each word
    $str = ucwords(trim($str));

    if ($first_Upper) {
      return str_replace(" ", "", $str);
    } else {
      return lcfirst(str_replace(" ", "", $str));
    }

  }

  public static function get_html_id($id){
    $id = strtr(drupal_strtolower($id), array(' ' => '-', '_' => '-', '[' => '-', ']' => ''));
    $id = preg_replace('/[^A-Za-z0-9\-_]/', '', $id);
    $id = preg_replace('/\-+/', '-', $id);
    return $id;
  }

  public static function replaceAccents($str) {
      $search = explode(",",
  "ç,æ,œ,á,é,í,ó,ú,à,è,ì,ò,ù,ä,ë,ï,ö,ü,ÿ,â,ê,î,ô,û,å,ø,Ø,Å,Á,À,Â,Ä,È,É,Ê,Ë,Í,Î,Ï,Ì,Ò,Ó,Ô,Ö,Ú,Ù,Û,Ü,Ÿ,Ç,Æ,Œ");
      $replace = explode(",",
  "c,ae,oe,a,e,i,o,u,a,e,i,o,u,a,e,i,o,u,y,a,e,i,o,u,a,o,O,A,A,A,A,A,E,E,E,E,I,I,I,I,O,O,O,O,U,U,U,U,Y,C,AE,OE");
      return str_replace($search, $replace, $str);
  }

  /**
   * CREATE THEME INFO REGIONS
   *
   * @return [type] [description]
   */
  public function themeInfo() {
    $themeInfo = "";
    foreach ($this->regions as $id => $region) {
      $themeInfo .= "<code>regions[".$region['key']."] = ".  $region['title']."</code>\n";
    }
    return $themeInfo;
  }

  public function sectionCSS($section)
  {

    $scss = new scssc();
    // $scss->setImportPaths(dirname(__FILE__));
    $scss->setImportPaths(SCSS_PATH);
    $scss->setFormatter(SCSS_FORMATTER);

    $attr = array(
      // 'id_wrapper',
      // 'id_container',

      'key',

      'background',
      'outer_background_color',
      'inner_background_color',
      'background_image',
      'background_type',
      'background_media',

      'text_color',
      'link_color',
      'hover_color',

      'primary_color',
      'dark_color',
      'light_color',

      'margin_bottom',
      'margin_left',
      'margin_right',
      'margin_top',

      'padding_bottom',
      'padding_left',
      'padding_right',
      'padding_top',

      'parallax_ratio',
      'parallax_speed',
    );

    foreach ($attr as $kkey => $value) {

      if( isset($section[$value]) && $section[$value] ) {
          $$value = $section[$value];
      } else {
        $$value = "false";
      }

    }


    if ( $background_media != "false" ) {

      $file = (array)file_load($background_media);

      if( isset($file[0])  && !$file[0] ) {

        $message = t('Missing File in <b>%section</b> (id: %media)',
          array(
            '%section' =>  $section['section_name'],
            '%media'   =>   $background_media
          )
        );
        drupal_set_message($message,'error');

        $background = "false";

      } else {
        $image = image_load($file['uri']);
        // $url = image_style_url('media_thumbnail', $file['uri'] );
        // $path = substr( file_create_url( $file['uri'] ) , strlen($GLOBALS['base_url']) );
        $path = substr( file_create_url( $file['uri'] ) , strlen($GLOBALS['base_url'])+1 );
        $path = base_path().$path;
        $background_media  = '"'.$path.'"';
      }

    }

    // $id_wrapper = $section['id_wrapper'];
    $id_wrapper = $this->get_html_id($this->camelCase($key)."Wrapper");
    // $id_container = $section['id_container'];
    $id_container = $this->get_html_id($this->camelCase($key)."Container");

    // debug($id_wrapper." <> ".$text_color);

$Scheme = <<<CS
  \$id_wrapper: {$id_wrapper};
  \$id_container: {$id_container};
  \$outer_background_color : {$outer_background_color};
  \$inner_background_color : {$inner_background_color};

  \$background: {$background};
  \$background_type: {$background_type};
  \$background_media: {$background_media};

  \$text_color : {$text_color};
  \$link_color : {$link_color};
  \$hover_color : {$hover_color};

  \$primary_color : {$primary_color};
  \$dark_color : {$dark_color};
  \$light_color : {$light_color};

  \$padding_bottom : {$padding_bottom};
  \$padding_left : {$padding_left};
  \$padding_right : {$padding_right};
  \$padding_top : {$padding_top};

  \$margin_bottom : {$margin_bottom };
  \$margin_left : {$margin_left};
  \$margin_right : {$margin_right};
  \$margin_top : {$margin_top};

  @import "section";
CS;

  $CSS = "";

    $CSS = $scss->compile($Scheme);

    $this->globalsectionCSS = $CSS;

    return $CSS;
  }


}

function biotic_get_theme() {
  $drupal_static = &drupal_static(__FUNCTION__);

  $key = $GLOBALS['theme_key'];
  if (!isset($drupal_static[$key])) {
    $drupal_static[$key] = new Biotic($key);
  }
  return $drupal_static[$key];
}
