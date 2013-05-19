<?php

# load menus for BootstrapHelper->menu()
require_once(dirname(__FILE__) . '/menus.php');

class BootstrapHelper {
    
    function __construct($wikka) {
        # wikka properties
        $this->wikka = $wikka;
        $this->user = $wikka->GetUser();
        $this->user_name = $wikka->GetUserName();
        $this->is_admin = $wikka->IsAdmin();
        $this->wiki_name = $wikka->GetWakkaName();
        $this->page_title = $wikka->PageTitle();
        $this->page_tag = $wikka->GetPageTag();
        $this->homepage_link = $wikka->href('',
            $this->config_ent('root_page'), '');
        $this->wikka_version = $this->set_wikka_version();
        $this->message = $wikka->GetRedirectMessage();
        
        # template paths
        $this->theme_path = $wikka->GetThemePath('/');
        $this->theme_css_path = sprintf('%s/css', $this->theme_path);
        $this->theme_js_path = sprintf('%s/js', $this->theme_path);
        
        # template properties
        $this->title_text = $this->set_title_text();
        $this->site_base = $this->set_site_base();
        $this->meta_robots = $this->set_meta_robots();
        $this->rss_revisions_link = $this->set_rss_link('revisions');
        $this->rss_recent_changes_link = $this->set_rss_link('recentchanges');
        $this->universal_edit_button = $this->set_universal_edit_button();
        $this->search_form = $this->set_search_form();
        $this->masthead_html = $this->set_masthead_html();
        $this->theme_hash = $this->config_ent('stylesheet_hash');
    }
    
    
    /*
     * Wikka Method Wrappers
     */
    function config_ent($config_key) {
        return $this->wikka->htmlspecialchars_ent(
            $this->wikka->GetConfigValue($config_key));
    }
    
    function link($href, $text, $title='', $class='') {
        $handler = '';
        $track = true;
        $escapeText = true;
        $assumePageExists = true;

        return $this->wikka->Link($href, $handler, $text, $track,
            $escapeText, $title, $class, $assumePageExists);
    }
    
    function echo_additional_headers() {
        $additional_headers_exist = isset($this->wikka->additional_headers) &&
            is_array($this->wikka->additional_headers) &&
            count($this->wikka->additional_headers);
        
        if ( $additional_headers_exist ) {
            foreach ($this->wikka->additional_headers as $header) {
                printf("%s\n", $header);
            }
        }
        else {
            echo '<!-- no additional headers -->';
        }
    }
    
    
    /*
     * Setters
     */
    function set_wikka_version() {
        $version = $this->wikka->GetWakkaVersion();
        $patch_level = '';
        
        if ( $this->wikka->GetWikkaPatchLevel() != '0' ) {
            $patch_level = sprintf('-p%s', $this->wikka->GetWikkaPatchLevel());
        }
            
        return sprintf('%s%s', $version, $patch_level);
    }
    
    function set_title_text() {
        return sprintf('%s : %s', $this->wiki_name, $this->page_title);
    }
    
    function set_site_base() {
        $site_base = WIKKA_BASE_URL;
        if ( substr_count($site_base, 'wikka.php?wakka=') > 0 ) {
            $site_base = substr($site_base,0,-16);
        }
        return $site_base;
    }
    
    function set_meta_robots() {
        $meta_f = '<meta name="robots" content="%s" />';
        $content = 'noindex, nofollow, noarchive';
        
        if ( $this->wikka->GetHandler() != 'show' ||
            $this->wikka->page["latest"] == 'N' ||
            $this->wikka->page["tag"] == 'SandBox' ) {
            $tag = sprintf($meta_f, $content, "\n");
        }
        else {
            $tag = '<!-- no meta robots tag for this page -->';
        }
        
        return $tag;
    }
    
    function set_rss_link($link_type) {
        # do not show for edit page
        if ( $this->wikka->GetHandler() == 'edit' ) {
            return '<!-- no rss link on edit page -->';
        }
        
        $link_f = '<link rel="%s" type="%s" title="%s" href="%s" />%s';
        $rel = 'alternate';
        $type = 'application/rss+xml';
        $xml_file = sprintf('%s.xml', $link_type);
        $href = $this->wikka->Href('revisions.xml', $this->page_tag);
        
        if ( $link_type == 'revisions' ) {
            $title_f = '%s : revisions for %s (RSS)';
            $title = sprintf($title_f, $this->wiki_name, $this->page_tag);
            $href = $this->wikka->Href($xml_file, $this->page_tag);
        }
        elseif ( $link_type == 'recentchanges' ) {
            $title_f = '%s : recently edited pages (RSS)';
            $title = sprintf($title_f, $this->wiki_name);
            $href = $this->wikka->Href($xml_file, $this->page_tag);
        }
        
        return sprintf($link_f, $rel, $type, $title, $href, "\n");
    }
    
    function set_universal_edit_button() {
        # UniversalEditButton
        # (http://universaleditbutton.org/Universal_Edit_Button) #779
        $button_f = '<link rel="%s" type="%s" title="%s" href="%s"/>';
        $rel = 'alternate';
        $type = 'application/x-wiki';
        $title = sprintf(T_("Click to edit %s"), $this->wikka->page['tag']);
        $href = $this->wikka->Href('edit', $this->wikka->page["tag"]);
        
        if ( $this->wikka->GetHandler() != 'edit' &&
            $this->wikka->HasAccess("write", $this->wikka->page["tag"]) ) {
            $button = sprintf($button_f, $rel, $type, $title, $href);
        }
        else {
            $button = '<!-- no ueb for this page -->';
        }
        
        return $button;
    }
    
    function set_search_form() {
        $html_f = "%s\n%s\n%s";
        
        $handler = '';
        $form_tag = 'TextSearch';
        $form_method ='get';
        $form_id = '';
        $form_class = 'navbar-search pull-right';
        
        $input_tag_f = '<input type="text" id="%s" class="%s" name="%s" ' .
            'placeholder="%s" />';
        $input_id = 'searchbox';
        $input_name = 'phrase';
        $input_class = 'searchbox search-query';
        $input_placeholder = 'Search';

        return sprintf($html_f,
            $this->wikka->FormOpen($handler, $form_tag, $form_method, $form_id,
                $form_class),
            sprintf($input_tag_f, $input_id, $input_class, $input_name,
                $input_placeholder),
            $this->wikka->FormClose());
    }
    
    function set_masthead_html() {
        $html_f = '%s : %s';
        $homepage_f = '<a id="homepage_link" href="%s">%s</a>';
        $backlinks_f = '<a href="%s" title="%s">%s</a>';
        
        $homepage_link = sprintf($homepage_f, $this->homepage_link,
            $this->wiki_name);
        
        $title = sprintf('Display a list of pages linking to %s',
            $this->page_tag);
        $backlinks_link = sprintf($backlinks_f,
            $this->wikka->href('backlinks', '', ''),
            $title,
            $this->page_tag);
          
        return sprintf($html_f, $homepage_link, $backlinks_link);
    }
    
    
    /*
     * Menu Methods
     */
    function menu($menu) {
        global $BootstrapMenus;
        $menu_array = $BootstrapMenus[$menu];
        
        if ( $this->wikka->IsAdmin() ) {
            $menu_items = $menu_array['admin'];
        }
        elseif ( $this->wikka->GetUser() ) {
            $menu_items = $menu_array['user'];
        }
        else {
            $menu_items = $menu_array['default'];
        }
        
        $menu_li = array();
        foreach( $menu_items as $item ) {
            if (is_array($item)) {
                $menu_li[] = $this->build_drop_down($item);
            }
            else {
                $menu_li[] = $this->menu_li($item);
            }
        }

        return $this->build_ul($menu_li, 'nav');
    }
    
    function build_drop_down($submenu, $href="#") {
        $keys = array_keys($submenu);
        $head = $keys[0];
        $lis = $submenu[$head];
        $active_class = '';        
        
        $toggle_f = "<a href=\"%s\" %s>\n%s\n<b class=\"caret\"></b></a>";
        $a_toggle = sprintf($toggle_f,
            $href,
            'class="dropdown-toggle" data-toggle="dropdown"',
            $head);
        
        $li_list = array();
        foreach ( $lis as $li ) {
            $li_list[] = $this->menu_li($li);
        }
        
        $sub_ul = $this->build_ul($li_list, 'dropdown-menu');

        $li_f = "<li class=\"dropdown%s\">\n%s\n%s\n</li>";
        return sprintf($li_f, $active_class, $a_toggle, $sub_ul);
    }
    
    function build_ul($li_list, $class=null, $id=null) {
        $ul_f = "<ul%s%s>\n%s\n</ul>";
        $lis = array();
        
        $ul_id = is_null($id) ? '' : sprintf(' id="%s"', $id);
        $ul_class = is_null($class) ? '' : sprintf(' class="%s"', $class);
        
        return sprintf($ul_f, $ul_id, $ul_class, implode("\n", $li_list));
    }
    
    function menu_li($wikka_item) {
        # pseudo-action formatters
        $contains_pseudo_action = preg_match('/<<([^>]+)>>/', $wikka_item,
            $match);
        if ( ! empty($contains_pseudo_action) ) {
            $tag = $match[1];
            $method = sprintf('%s_pseudoaction', $tag);
            $wikka_item = $this->$method($wikka_item, sprintf('<<%s>>', $tag));
        }

        $active = strpos($wikka_item, $this->wikka->GetPageTag()) !== false;
        
        $class = '';
        if ( $active ) {
            $class = ' class="active"';
        }
        
        $li_f = "<li%s>%s</li>";
        return sprintf($li_f, $class, $this->wikka->Format($wikka_item));
    }
    
    
    /*
     * Pseudo-Actions (used by menus -- see menu_li)
     */
    function username_pseudoaction($wikka_item, $tag) {
        return str_replace($tag, $this->user_name, $wikka_item);
    }
    
    function logout_pseudoaction() {
        # must escape wiki formatting
        return '""<a class="logout-click" href="#">Logout</a>""';
    }
    
    
    /*
     * Debug Methods
     */
    function output_sql_debugging() {
        $html_f = <<<HTML5
    <div id="sql_debug" class="smallprint">
        <h4>Query Log</h4>
        <table>
          <thead>
            <tr><th>query</th><th>time</th></tr>
          </thead>
          <tbody>
            %s
            <tr class="total">
                <td class="query">total time</td>
                <td class="time">%0.4f</td>
            </tr>
          </tbody>          
        </table>
    </div>        
HTML5;
        
        $query_tr = array();
        $tr_f = '<tr><td class="query">%s</td><td class="time">%0.4f</td></tr>';
        foreach ($this->wikka->queryLog as $query) {
            $query_tr[] = sprintf($tr_f, $query['query'], $query['time']);
        }

        printf($html_f, implode("\n", $query_tr), $this->get_load_time);
    }
    
    function get_load_time() {
        global $tstart;
        return $this->wikka->microTimeDiff($tstart);
    }
    
    function output_load_time() {
        $f = T_("Page was generated in %.4f seconds");
        return sprintf($f, $this->get_load_time());
    }
}