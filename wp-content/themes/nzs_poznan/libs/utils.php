<?php
	
	
	 function cutText($text, $maxLength){
        
        $maxLength++;

        $return = '';
        if (mb_strlen($text) > $maxLength) {
            $subex = mb_substr($text, 0, $maxLength - 5);
            $exwords = explode(' ', $subex);
            $excut = - ( mb_strlen($exwords[count($exwords) - 1]) );
            if ($excut < 0) {
                $return = mb_substr($subex, 0, $excut);
            } else {
                $return = $subex;
            }
            $return .= '[...]';
        } else {
            $return = $text;
        }
        
        return $return;
    }
	
	
	function fetchRecentComments($limit = 3) {

	 global $wpdb;
        //dla bezpieczeństwa
		$limit = (int)$limit;
		
        $res = $wpdb->get_results("
            SELECT C.*, P.post_title
                FROM {$wpdb->comments} C
                    LEFT JOIN {$wpdb->posts} P ON C.comment_post_ID = P.ID
                WHERE comment_approved = 1
                ORDER BY comment_date_gmt DESC
                LIMIT {$limit}
        ");
                
        return $res;

}

?>


<?php function the_excerpt_max_charlength($charlength) {
	$excerpt = get_the_excerpt();
	$charlength++;

	if ( mb_strlen( $excerpt ) > $charlength ) {
		$subex = mb_substr( $excerpt, 0, $charlength - 5 );
		$exwords = explode( ' ', $subex );
		$excut = - ( mb_strlen( $exwords[ count( $exwords ) - 1 ] ) );
		if ( $excut < 0 ) {
			echo mb_substr( $subex, 0, $excut );
		} else {
			echo $subex;
		}
		echo '[...]';
	} else {
		echo $excerpt;
	}
}


function getQueryParams(){
        global $query_string;
        $parts = explode('&', $query_string);
        
        $return = array();
        foreach($parts as $part){
            $tmp = explode('=', $part);
            $return[$tmp[0]] = trim(urldecode($tmp[1]));
        }
        
        return $return;
    }  

function getQuerySingleParam($name){
        $qparams = getQueryParams();
        
        if(isset($qparams[$name])){
            return $qparams[$name];
        }
        
        return NULL;
    }

function getCurrentPageUrl(){
        $pageURL = 'http';
        
	if(isset($_SERVER["HTTPS"])){
            if($_SERVER["HTTPS"] == "on") {
                $pageURL .= "s";
            }
	}
        
	$pageURL .= "://";
        
	if($_SERVER["SERVER_PORT"] != "80"){
            $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
	}else{
            $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
	}
        
	return $pageURL;
    }

add_filter('posts_where', 'title_like_posts_where', 10, 2);
    function title_like_posts_where( $where, &$wp_query ) {
        global $wpdb;
        
        if ($post_title_like = $wp_query->get('post_title_like')){
            $where .= ' AND '.$wpdb->posts.'.post_title LIKE \'%'.esc_sql(like_escape($post_title_like)).'%\'';
        }
        
        return $where;
}

?>