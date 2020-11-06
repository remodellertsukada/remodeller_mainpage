<?php
add_action( 'wp_footer', 'add_thanks_page' );
function add_thanks_page() {
	// 求職者登録完了
	if( get_the_ID() == '28' ){  //ここにIDを入れる
		echo <<< EOD
		<script>
		document.addEventListener( 'wpcf7mailsent', function( event ) {
		  location = '/completerecruitreg/';
		}, false );
		</script>
		EOD;
	}

	// 事業者登録完了
	if( get_the_ID() == '29' ){  //ここにIDを入れる
		echo <<< EOD
		<script>
		document.addEventListener( 'wpcf7mailsent', function( event ) {
		  location = '/completeemployerreg/';
		}, false );
		</script>
		EOD;
	}

	// お問合せ完了
	if( get_the_ID() == '32' ){  //ここにIDを入れる
		echo <<< EOD
		<script>
		document.addEventListener( 'wpcf7mailsent', function( event ) {
		  location = '/completecontact/';
		}, false );
		</script>
		EOD;
	}
}
?>